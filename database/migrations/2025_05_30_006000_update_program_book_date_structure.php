<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Update program_books table - first add nullable columns
        Schema::table('program_books', function (Blueprint $table) {
            $table->date('start_date')->nullable()->after('title');
            $table->date('end_date')->nullable()->after('start_date');
        });

        // Update existing program_books records to use conference dates
        DB::statement('
            UPDATE program_books 
            SET start_date = (
                SELECT start_date 
                FROM conferences 
                WHERE conferences.id = program_books.conference_id
            ),
            end_date = (
                SELECT end_date 
                FROM conferences 
                WHERE conferences.id = program_books.conference_id
            )
        ');

        // Now make the columns non-nullable
        Schema::table('program_books', function (Blueprint $table) {
            $table->date('start_date')->nullable(false)->change();
            $table->date('end_date')->nullable(false)->change();
        });

        // Drop the old date column
        Schema::table('program_books', function (Blueprint $table) {
            $table->dropColumn('date');
        });

        // For sessions table - first backup existing datetime data
        DB::statement('
            ALTER TABLE sessions 
            ADD COLUMN temp_start_datetime timestamp,
            ADD COLUMN temp_end_datetime timestamp
        ');
        
        DB::statement('
            UPDATE sessions 
            SET temp_start_datetime = start_time,
                temp_end_datetime = end_time
        ');

        // Drop existing datetime columns
        Schema::table('sessions', function (Blueprint $table) {
            $table->dropColumn(['start_time', 'end_time']);
        });

        // Add new date and time columns
        Schema::table('sessions', function (Blueprint $table) {
            $table->date('date')->nullable()->after('description');
            $table->time('start_time')->nullable()->after('date');
            $table->time('end_time')->nullable()->after('start_time');
        });

        // Update existing sessions to extract date and time from backed up datetime fields (PostgreSQL syntax)
        DB::statement('
            UPDATE sessions 
            SET date = temp_start_datetime::date,
                start_time = temp_start_datetime::time,
                end_time = temp_end_datetime::time
        ');

        // Now make the columns non-nullable
        Schema::table('sessions', function (Blueprint $table) {
            $table->date('date')->nullable(false)->change();
            $table->time('start_time')->nullable(false)->change();
            $table->time('end_time')->nullable(false)->change();
        });

        // Drop temporary columns
        Schema::table('sessions', function (Blueprint $table) {
            $table->dropColumn(['temp_start_datetime', 'temp_end_datetime']);
        });

        // For presentations table - first backup existing datetime data
        DB::statement('
            ALTER TABLE presentations 
            ADD COLUMN temp_start_datetime timestamp,
            ADD COLUMN temp_end_datetime timestamp
        ');
        
        DB::statement('
            UPDATE presentations 
            SET temp_start_datetime = start_time,
                temp_end_datetime = end_time
        ');

        // Drop existing datetime columns
        Schema::table('presentations', function (Blueprint $table) {
            $table->dropColumn(['start_time', 'end_time']);
        });

        // Add new time columns
        Schema::table('presentations', function (Blueprint $table) {
            $table->time('start_time')->nullable()->after('abstract');
            $table->time('end_time')->nullable()->after('start_time');
        });

        // Update existing presentations to extract time from backed up datetime fields (PostgreSQL syntax)
        DB::statement('
            UPDATE presentations 
            SET start_time = temp_start_datetime::time,
                end_time = temp_end_datetime::time
        ');

        // Now make the columns non-nullable
        Schema::table('presentations', function (Blueprint $table) {
            $table->time('start_time')->nullable(false)->change();
            $table->time('end_time')->nullable(false)->change();
        });

        // Drop temporary columns
        Schema::table('presentations', function (Blueprint $table) {
            $table->dropColumn(['temp_start_datetime', 'temp_end_datetime']);
        });
    }

    public function down()
    {
        // Revert presentations table
        Schema::table('presentations', function (Blueprint $table) {
            $table->dropColumn(['start_time', 'end_time']);
            $table->dateTime('start_time')->after('abstract');
            $table->dateTime('end_time')->after('start_time');
        });

        // Revert sessions table
        Schema::table('sessions', function (Blueprint $table) {
            $table->dropColumn(['date', 'start_time', 'end_time']);
            $table->dateTime('start_time')->after('description');
            $table->dateTime('end_time')->after('start_time');
        });

        // Revert program_books table
        Schema::table('program_books', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'end_date']);
            $table->date('date')->after('title');
        });
    }
}; 