<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('summit_registrations', function (Blueprint $table) {
            $table->string('organization_type')->nullable()->after('organization');
            $table->string('organization_type_other')->nullable()->after('organization_type');
            $table->string('organization_size')->nullable()->after('organization_type_other');
            $table->string('participation_budget_support')->nullable()->after('organization_size');
        });
    }

    public function down(): void
    {
        Schema::table('summit_registrations', function (Blueprint $table) {
            $table->dropColumn([
                'organization_type',
                'organization_type_other',
                'organization_size',
                'participation_budget_support',
            ]);
        });
    }
};
