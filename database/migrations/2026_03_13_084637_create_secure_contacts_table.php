<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('secure_contacts', function (Blueprint $table) {
            $table->id();
            
            // Plain text field (not encrypted - for display)
            $table->string('name');
            
            // Encrypted fields (stored as text)
            $table->text('email')->nullable();
            $table->text('phone')->nullable();
            
            // Hash indexes for searching encrypted fields (exact match only)
            $table->string('email_hash')->nullable()->index();
            $table->string('phone_hash')->nullable()->index();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('secure_contacts');
    }
};