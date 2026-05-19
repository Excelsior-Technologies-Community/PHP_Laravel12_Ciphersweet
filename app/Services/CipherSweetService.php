<?php

namespace App\Services;

use ParagonIE\CipherSweet\Backend\ModernCrypto;
use ParagonIE\CipherSweet\BlindIndex;
use ParagonIE\CipherSweet\CipherSweet;
use ParagonIE\CipherSweet\EncryptedField;
use ParagonIE\CipherSweet\EncryptedRow;
use ParagonIE\CipherSweet\KeyProvider\StringProvider;

class CipherSweetService
{
    private CipherSweet $engine;
    private EncryptedRow $encryptedRow;

    public function __construct()
    {
        // IMPORTANT: Store this key in .env file!
        // Generate: php artisan cipher-sweet:generate-key
        $backend = new ModernCrypto();
        
        $keyProvider = new StringProvider(
            env('CIPHERSWEET_KEY', '')
        );
        
        $this->engine = new CipherSweet($keyProvider, $backend);
        
        $this->encryptedRow = (new EncryptedRow($this->engine, 'secure_contacts'))
            ->addField('email')
            ->addBlindIndex('email', new BlindIndex('email_index', 32, true))
            ->addField('phone')
            ->addBlindIndex('phone', new BlindIndex('phone_index', 32, false));
    }

    public function encryptAndIndex(string $field, string $value): array
    {
        $fieldDefinition = $this->encryptedRow->getField($field);
        $encrypted = $fieldDefinition->prepareForStorage($value);
        
        $blindIndexes = [];
        foreach ($fieldDefinition->getBlindIndexes() as $index) {
            $blindIndexes[$index->getName()] = $fieldDefinition->getBlindIndex($value, $index->getName());
        }
        
        return [
            'encrypted' => $encrypted,
            'blind_indexes' => $blindIndexes
        ];
    }

    public function decryptValue(string $field, string $encryptedValue): string
    {
        $fieldDefinition = $this->encryptedRow->getField($field);
        return $fieldDefinition->decryptValue($encryptedValue);
    }

    public function getBlindIndexForSearch(string $field, string $searchValue): string
    {
        $fieldDefinition = $this->encryptedRow->getField($field);
        $index = $fieldDefinition->getBlindIndex($searchValue, $field . '_index');
        return $index;
    }

    public function getEngine(): CipherSweet
    {
        return $this->engine;
    }

    public function getEncryptedRow(): EncryptedRow
    {
        return $this->encryptedRow;
    }
}