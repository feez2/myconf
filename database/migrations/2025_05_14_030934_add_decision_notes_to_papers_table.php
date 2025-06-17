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
            $table->text('decision_notes')->nullable();
            $table->timestamp('decision_made_at')->nullable();
            $table->foreignId('decision_made_by')->nullable()->constrained('users');
            $table->date('camera_ready_deadline')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('papers', function (Blueprint $table) {
            $table->dropColumn('decision_notes');
            $table->dropColumn('decision_made_at');
            $table->dropForeign(['decision_made_by']);
            $table->dropColumn('decision_made_by');
            $table->dropColumn('camera_ready_deadline');
        });
    }
}; 