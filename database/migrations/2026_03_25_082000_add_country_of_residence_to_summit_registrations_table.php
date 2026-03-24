<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('summit_registrations', function (Blueprint $table) {
            $table->string('country_of_residence', 255)->nullable()->after('country');
        });
    }

    public function down(): void
    {
        Schema::table('summit_registrations', function (Blueprint $table) {
            $table->dropColumn('country_of_residence');
        });
    }
};
