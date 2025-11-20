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
        // TABLE USERS (merge Jetstream + custom field)
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id'); // primary key autoincrement
            $table->string('name', 100);
            $table->string('nim', 9)->unique()->nullable(); // custom, bisa unique atau nullable
            $table->string('email', 100)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 255);
            $table->unsignedBigInteger('program_studi'); // custom
            $table->rememberToken();
            $table->foreignId('current_team_id')->nullable(); // Jetstream
            $table->string('profile_photo_path', 2048)->nullable(); // Jetstream
            $table->timestamps();

            // Foreign key custom
            $table->foreign('program_studi')->references('id')->on('programs')->onDelete('cascade');
        });

        // TABLE PASSWORD RESET TOKENS (Jetstream/Laravel default)
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // TABLE SESSIONS (Jetstream/Laravel default)
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
