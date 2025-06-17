<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Conference chairs table
        Schema::create('conference_chairs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conference_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['conference_id', 'user_id']);
        });

        // Program chairs table
        Schema::create('conference_program_chairs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conference_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['conference_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('conference_program_chairs');
        Schema::dropIfExists('conference_chairs');
    }
};
