<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('papers', function (Blueprint $table) {
            $table->index(['conference_id', 'status']);
            $table->index(['conference_id', 'approved_for_proceedings']);
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->index(['paper_id', 'score']);
        });
    }

    public function down()
    {
        Schema::table('papers', function (Blueprint $table) {
            $table->dropIndex(['conference_id', 'status']);
            $table->dropIndex(['conference_id', 'approved_for_proceedings']);
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex(['paper_id', 'score']);
        });
    }
};
