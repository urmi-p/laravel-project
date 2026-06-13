<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('promo_codes')) {
            return;
        }

        Schema::create('promo_codes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('creator_id')->index();
            $table->string('code', 100);
            $table->string('normalized_code', 100)->index();
            $table->enum('discount_type', ['fixed', 'percentage']);
            $table->decimal('discount_value', 12, 2);
            $table->timestamp('expires_at')->nullable()->index();
            $table->unsignedInteger('usage_limit_total')->nullable();
            $table->unsignedInteger('usage_limit_per_user')->nullable();
            $table->unsignedInteger('used_count')->default(0);
            $table->enum('is_active', ['yes', 'no'])->default('yes')->index();
            $table->timestamp('first_used_at')->nullable();
            $table->timestamp('disabled_at')->nullable();
            $table->unsignedBigInteger('disabled_by_admin_id')->nullable()->index();
            $table->timestamps();

            $table->unique(['creator_id', 'normalized_code'], 'promo_codes_creator_normalized_unique');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('promo_codes')) {
            return;
        }

        Schema::drop('promo_codes');
    }
};
