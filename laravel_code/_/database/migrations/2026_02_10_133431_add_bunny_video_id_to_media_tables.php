<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('media', function (Blueprint $table) {
            $table->string('bunny_video_id')->nullable()->after('video');
        });
        Schema::table('media_messages', function (Blueprint $table) {
            $table->string('bunny_video_id')->nullable()->after('file');
        });
        Schema::table('media_reels', function (Blueprint $table) {
            $table->string('bunny_video_id')->nullable()->after('name');
        });
        Schema::table('media_stories', function (Blueprint $table) {
            $table->string('bunny_video_id')->nullable()->after('name');
        });
        Schema::table('media_welcome_messages', function (Blueprint $table) {
            $table->string('bunny_video_id')->nullable()->after('file');
        });
        Schema::table('vaults', function (Blueprint $table) {
            $table->string('bunny_video_id')->nullable()->after('file');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('media', function (Blueprint $table) {
            $table->dropColumn('bunny_video_id');
        });
        Schema::table('media_messages', function (Blueprint $table) {
            $table->dropColumn('bunny_video_id');
        });
        Schema::table('media_reels', function (Blueprint $table) {
            $table->dropColumn('bunny_video_id');
        });
        Schema::table('media_stories', function (Blueprint $table) {
            $table->dropColumn('bunny_video_id');
        });
        Schema::table('media_welcome_messages', function (Blueprint $table) {
            $table->dropColumn('bunny_video_id');
        });
        Schema::table('vaults', function (Blueprint $table) {
            $table->dropColumn('bunny_video_id');
        });
    }
};
