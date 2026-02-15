<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('summit_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone', 50);
            $table->string('country')->nullable();
            $table->string('organization');
            $table->string('job_title');
            $table->string('website', 500)->nullable();
            $table->string('passport_number', 50);
            $table->date('passport_expiry')->nullable();
            $table->date('arrival_date');
            $table->date('departure_date');
            $table->string('price_tier', 20);
            $table->boolean('field_visit')->default(false);
            $table->string('accessibility_needed', 10);
            $table->string('accessibility_details', 1000)->nullable();
            $table->string('dietary')->nullable();
            $table->string('dietary_other', 255)->nullable();
            $table->json('heard_from')->nullable();
            $table->string('heard_from_other', 255)->nullable();
            $table->json('additional_members')->nullable();
            $table->string('cofsa_member', 10);
            $table->string('cofsa_newsletter', 10);
            $table->string('emergency_name');
            $table->string('emergency_phone', 50);
            $table->string('emergency_email');
            $table->string('emergency_relation');
            $table->string('photo')->nullable();
            $table->string('status', 20)->default('pending');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('summit_registrations');
    }
};
