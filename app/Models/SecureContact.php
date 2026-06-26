<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class SecureContact extends Model
{
    protected $table = 'secure_contacts';

    protected $fillable = [
        'name', 'email', 'phone', 'email_hash', 'phone_hash',
        'email_prefix_hashes', 'phone_prefix_hashes'
    ];

    protected $casts = [
        'email_prefix_hashes' => 'array',
        'phone_prefix_hashes' => 'array',
    ];

    const MIN_PREFIX_LEN = 3;

    protected static function boot()
    {
        parent::boot();

        static::retrieved(function ($contact) {
            AuditLog::create([
                'user_id' => Auth::id(),
                'secure_contact_id' => $contact->id,
                'action' => 'viewed',
                'ip_address' => Request::ip(),
            ]);
        });
    }

    public function setEmailAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['email'] = null;
            $this->attributes['email_hash'] = null;
            $this->attributes['email_prefix_hashes'] = null;
            return;
        }

        $normalized = strtolower(trim($value));

        $this->attributes['email'] = Crypt::encryptString($value);
        $this->attributes['email_hash'] = hash('sha256', $normalized);
        $this->attributes['email_prefix_hashes'] = json_encode(
            self::generatePrefixHashes($normalized)
        );
    }

    public function getEmailAttribute($value)
    {
        if (empty($value)) {
            return null;
        }

        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return '[Encrypted - Cannot decrypt]';
        }
    }

    public function setPhoneAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['phone'] = null;
            $this->attributes['phone_hash'] = null;
            $this->attributes['phone_prefix_hashes'] = null;
            return;
        }

        $normalized = trim($value);

        $this->attributes['phone'] = Crypt::encryptString($value);
        $this->attributes['phone_hash'] = hash('sha256', $normalized);
        $this->attributes['phone_prefix_hashes'] = json_encode(
            self::generatePrefixHashes($normalized)
        );
    }

    public function getPhoneAttribute($value)
    {
        if (empty($value)) {
            return null;
        }

        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return '[Encrypted - Cannot decrypt]';
        }
    }

    public static function generatePrefixHashes(string $normalized): array
    {
        $hashes = [];
        $len = strlen($normalized);

        for ($i = self::MIN_PREFIX_LEN; $i <= $len; $i++) {
            $prefix = substr($normalized, 0, $i);
            $hashes[] = hash('sha256', $prefix);
        }

        return $hashes;
    }

    public function scopeSearchByEmail($query, $email)
    {
        $normalized = strtolower(trim($email));

        if (strlen($normalized) < self::MIN_PREFIX_LEN) {
            $hash = hash('sha256', $normalized);
            return $query->where('email_hash', $hash);
        }

        $prefixHash = hash('sha256', $normalized);
        return $query->whereJsonContains('email_prefix_hashes', $prefixHash);
    }

    public function scopeSearchByPhone($query, $phone)
    {
        $normalized = trim($phone);

        if (strlen($normalized) < self::MIN_PREFIX_LEN) {
            $hash = hash('sha256', $normalized);
            return $query->where('phone_hash', $hash);
        }

        $prefixHash = hash('sha256', $normalized);
        return $query->whereJsonContains('phone_prefix_hashes', $prefixHash);
    }

    public static function maskEmail(?string $email): string
    {
        if (empty($email) || !str_contains($email, '@')) {
            return '***';
        }

        [$local, $domain] = explode('@', $email, 2);

        $visible = substr($local, 0, min(2, strlen($local)));

        return $visible . str_repeat('*', max(strlen($local) - strlen($visible), 3)) . '@' . $domain;
    }

    public static function maskPhone(?string $phone): string
    {
        if (empty($phone)) {
            return '***';
        }

        $digits = preg_replace('/\D/', '', $phone);
        $len = strlen($digits);

        if ($len <= 4) {
            return str_repeat('*', $len);
        }

        $visibleStart = substr($digits, 0, 2);
        $visibleEnd = substr($digits, -4);

        return $visibleStart . str_repeat('*', $len - 6) . $visibleEnd;
    }
}