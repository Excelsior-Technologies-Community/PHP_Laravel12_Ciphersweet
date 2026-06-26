<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('secure_contacts', function (Blueprint $table) {
            $table->json('email_prefix_hashes')->nullable()->after('email_hash');
            $table->json('phone_prefix_hashes')->nullable()->after('phone_hash');
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('secure_contact_id');
            $table->string('action');
            $table->string('ip_address')->nullable();
            $table->timestamps();

            $table->index('secure_contact_id');
            $table->index('user_id');
        });
    }

    public function down()
    {
        Schema::table('secure_contacts', function (Blueprint $table) {
            $table->dropColumn(['email_prefix_hashes', 'phone_prefix_hashes']);
        });

        Schema::dropIfExists('audit_logs');
    }
};