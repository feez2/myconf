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
        Schema::table('conferences', function (Blueprint $table) {
            $table->date('start_date')->change();
            $table->date('end_date')->change();
            
            $table->date('submission_deadline')->nullable()->after('end_date');
            $table->date('review_deadline')->nullable()->after('submission_deadline');
        });
    }

    public function down()
    {
        Schema::table('conferences', function (Blueprint $table) {
            $table->dropColumn(['submission_deadline', 'review_deadline']);
            
            $table->dateTime('start_date')->change();
            $table->dateTime('end_date')->change();
        });
    }
};
