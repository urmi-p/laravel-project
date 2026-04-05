<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('admin_settings') || !Schema::hasColumn('admin_settings', 'home_style')) {
            return;
        }

        DB::table('admin_settings')->whereNull('home_style')->update(['home_style' => 0]);

        $driver = DB::getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement('ALTER TABLE `admin_settings` MODIFY `home_style` INT UNSIGNED NOT NULL DEFAULT 0');
        } elseif ($driver === 'pgsql') {
            DB::statement('ALTER TABLE admin_settings ALTER COLUMN home_style SET DEFAULT 0');
            DB::statement('ALTER TABLE admin_settings ALTER COLUMN home_style SET NOT NULL');
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('admin_settings') || !Schema::hasColumn('admin_settings', 'home_style')) {
            return;
        }

        $driver = DB::getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement('ALTER TABLE `admin_settings` MODIFY `home_style` INT UNSIGNED NOT NULL');
        } elseif ($driver === 'pgsql') {
            DB::statement('ALTER TABLE admin_settings ALTER COLUMN home_style DROP DEFAULT');
        }
    }
};
