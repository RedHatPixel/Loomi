<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->text('story')->nullable()->after('description');
            $table->string('website')->nullable()->after('logo');
            $table->string('instagram')->nullable()->after('website');
            $table->string('tiktok')->nullable()->after('instagram');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn(['story', 'website', 'instagram', 'tiktok']);
        });
    }
};
