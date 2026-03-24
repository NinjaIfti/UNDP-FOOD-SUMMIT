<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('summit_registrations', function (Blueprint $table) {
            $table->string('price_tier_raw')->nullable()->after('price_tier');
            $table->string('price_tier_name')->nullable()->after('price_tier_raw');
            $table->boolean('confirm_tier')->default(false)->after('price_tier_name');
            $table->decimal('flexible_support_amount', 10, 2)->nullable()->after('confirm_tier');
            $table->text('subsidized_price_justification')->nullable()->after('flexible_support_amount');
        });
    }

    public function down(): void
    {
        Schema::table('summit_registrations', function (Blueprint $table) {
            $table->dropColumn([
                'price_tier_raw',
                'price_tier_name',
                'confirm_tier',
                'flexible_support_amount',
                'subsidized_price_justification',
            ]);
        });
    }
};
