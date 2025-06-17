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
            $table->string('camera_ready_file')->nullable()->after('file_path');
            $table->string('copyright_form_file')->nullable()->after('camera_ready_file');
            $table->timestamp('camera_ready_submitted_at')->nullable()->after('copyright_form_file');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('papers', function (Blueprint $table) {
            $table->dropColumn([
                'camera_ready_file',
                'copyright_form_file',
                'camera_ready_submitted_at'
            ]);
        });
    }
}; 