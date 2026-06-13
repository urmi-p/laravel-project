<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('promo_code_usages')) {
            return;
        }

        Schema::create('promo_code_usages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('promo_code_id')->index();
            $table->unsignedBigInteger('creator_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('subscription_id')->nullable()->index();
            $table->unsignedBigInteger('transaction_id')->nullable()->index();
            $table->unsignedBigInteger('plan_id')->nullable()->index();
            $table->string('plan_interval', 50)->nullable();
            $table->string('gateway_name', 100)->nullable()->index();
            $table->string('gateway_reference', 190)->nullable()->index();
            $table->string('checkout_token', 190)->nullable()->unique();
            $table->decimal('original_amount', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('charged_amount', 12, 2)->default(0);
            $table->decimal('creator_earning_impact', 12, 2)->default(0);
            $table->decimal('platform_commission_amount', 12, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->enum('status', ['pending', 'completed', 'failed', 'reverted'])->default('pending')->index();
            $table->timestamp('used_at')->nullable()->index();
            $table->timestamps();

            $table->index(['promo_code_id', 'status'], 'promo_code_usages_code_status_index');
            $table->index(['promo_code_id', 'user_id', 'status'], 'promo_code_usages_code_user_status_index');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('promo_code_usages')) {
            return;
        }

        Schema::drop('promo_code_usages');
    }
};
