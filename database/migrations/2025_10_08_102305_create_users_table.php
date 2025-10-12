<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id'); // Atau $table->id(); jika ingin id default autoincrement id
            $table->string('name', 100);
            $table->string('nim', 9)->unique()->nullable();
            $table->string('email', 100)->unique();
            $table->timestamp('email_verified_at')->nullable(); // untuk fitur verifikasi email
            $table->string('password', 255);
            $table->unsignedBigInteger('program_studi'); 
            $table->rememberToken(); // untuk fitur remember me, WAJIB kalau pakai Laravel Auth/Breeze
            $table->timestamps();

            // Foreign key ke tabel prodi/programs
            $table->foreign('program_studi')->references('id')->on('programs')->onDelete('cascade');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
