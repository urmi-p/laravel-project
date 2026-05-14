<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('admin_settings') || Schema::hasColumn('admin_settings', 'apple_login')) {
            return;
        }

        Schema::table('admin_settings', function (Blueprint $table) {
            $table->enum('apple_login', ['on', 'off'])->default('off');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('admin_settings') || ! Schema::hasColumn('admin_settings', 'apple_login')) {
            return;
        }

        Schema::table('admin_settings', function (Blueprint $table) {
            $table->dropColumn('apple_login');
        });
    }
};
