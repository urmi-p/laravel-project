<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('promo_code_histories')) {
            return;
        }

        Schema::create('promo_code_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('promo_code_id')->index();
            $table->unsignedBigInteger('actor_user_id')->nullable()->index();
            $table->enum('actor_role', ['creator', 'admin', 'system'])->default('system');
            $table->string('event_type', 100)->index();
            $table->longText('old_values')->nullable();
            $table->longText('new_values')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('promo_code_histories')) {
            return;
        }

        Schema::drop('promo_code_histories');
    }
};
