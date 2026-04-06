<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('summit_registrations', function (Blueprint $table) {
            $table->decimal('admin_override_price', 10, 2)->nullable()->after('flexible_support_amount');
        });
    }

    public function down(): void
    {
        Schema::table('summit_registrations', function (Blueprint $table) {
            $table->dropColumn('admin_override_price');
        });
    }
};
