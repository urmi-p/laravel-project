<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('updates', function (Blueprint $table) {
            if (!Schema::hasColumn('updates', 'hide_likes_count')) {
                $table->boolean('hide_likes_count')->default(false)->after('likes_extras');
            }

            if (!Schema::hasColumn('updates', 'turn_off_comments')) {
                $table->boolean('turn_off_comments')->default(false)->after('hide_likes_count');
            }
        });
    }

    public function down(): void
    {
        Schema::table('updates', function (Blueprint $table) {
            if (Schema::hasColumn('updates', 'turn_off_comments')) {
                $table->dropColumn('turn_off_comments');
            }

            if (Schema::hasColumn('updates', 'hide_likes_count')) {
                $table->dropColumn('hide_likes_count');
            }
        });
    }
};

