<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * @return void
     */
    public function up(): void
    {
        Schema::table('user_cities', function (Blueprint $table) {
            $table->smallInteger('threshold_temperature')->after('city_id')->default(0);
            $table->smallInteger('threshold_uv')->after('city_id')->default(0);
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::table('user_cities', function (Blueprint $table) {
            $table->dropColumn('threshold_temperature');
            $table->smallInteger('threshold_uv');
        });
    }
};
