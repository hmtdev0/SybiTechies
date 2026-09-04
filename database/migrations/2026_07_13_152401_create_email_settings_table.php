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
        Schema::create('email_settings', function (Blueprint $table) {
            $table->id();

            // SMTP
            $table->string('smtp_host')->nullable();
            $table->unsignedInteger('smtp_port')->nullable();
            $table->string('smtp_username')->nullable();
            $table->text('smtp_password')->nullable();
            $table->string('smtp_encryption')->nullable();
            $table->string('from_address')->nullable();
            $table->string('from_name')->nullable();

            // Master switch — when off, the contact form only stores the
            // message in the database and no email logic runs at all.
            $table->boolean('email_enabled')->default(false);

            // Auto-reply ("Thank you for contacting us") sent to the sender.
            $table->boolean('autoreply_enabled')->default(true);
            $table->string('autoreply_subject')->nullable();
            $table->longText('autoreply_body')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_settings');
    }
};
