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
        Schema::table('papers', function (Blueprint $table) {
            $table->string('camera_ready_path')->nullable()->after('status');
            $table->boolean('approved_for_proceedings')->default(false)->after('camera_ready_path');
            $table->text('proceeding_comments')->nullable()->after('approved_for_proceedings');
            $table->dateTime('proceeding_approval_date')->nullable()->after('proceeding_comments');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('papers', function (Blueprint $table) {
            //
        });
    }
};
