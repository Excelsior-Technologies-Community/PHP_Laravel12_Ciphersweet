<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class SecureContact extends Model
{
    protected $table = 'secure_contacts';
    
    protected $fillable = [
        'name', 'email', 'phone', 'email_hash', 'phone_hash'
    ];
    
    // Auto-encrypt email when setting
    public function setEmailAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['email'] = null;
            $this->attributes['email_hash'] = null;
            return;
        }
        
        // Store encrypted value
        $this->attributes['email'] = Crypt::encryptString($value);
        // Store hash for searching (SHA256 for exact match)
        $this->attributes['email_hash'] = hash('sha256', strtolower(trim($value)));
    }
    
    // Auto-decrypt email when accessing
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
    
    // Auto-encrypt phone when setting
    public function setPhoneAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['phone'] = null;
            $this->attributes['phone_hash'] = null;
            return;
        }
        
        $this->attributes['phone'] = Crypt::encryptString($value);
        $this->attributes['phone_hash'] = hash('sha256', trim($value));
    }
    
    // Auto-decrypt phone when accessing
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
    
    // Scope for searching by encrypted email
    public function scopeSearchByEmail($query, $email)
    {
        $hash = hash('sha256', strtolower(trim($email)));
        return $query->where('email_hash', $hash);
    }
    
    // Scope for searching by encrypted phone
    public function scopeSearchByPhone($query, $phone)
    {
        $hash = hash('sha256', trim($phone));
        return $query->where('phone_hash', $hash);
    }
}