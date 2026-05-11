<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelCipherSweet\Contracts\CipherSweetEncrypted;
use Spatie\LaravelCipherSweet\Concerns\UsesCipherSweet;
use ParagonIE\CipherSweet\EncryptedRow;
use ParagonIE\CipherSweet\BlindIndex;

class SecureContact extends Model implements CipherSweetEncrypted
{
    use UsesCipherSweet;

    protected $fillable = [
        'name',
        'email',
        'phone'
    ];

    public static function configureCipherSweet(EncryptedRow $encryptedRow): void
    {
        $encryptedRow

            ->addField('email')
            ->addBlindIndex(
                'email',
                new BlindIndex('email_index')
            )

            ->addField('phone')
            ->addBlindIndex(
                'phone',
                new BlindIndex('phone_index')
            );
    }
}