<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('program_books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conference_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->date('date');
            $table->text('welcome_message')->nullable();
            $table->text('general_information')->nullable();
            $table->string('cover_image_path')->nullable();
            $table->timestamps();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_book_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->string('location');
            $table->string('session_chair')->nullable();
            $table->integer('order')->default(0);
            $table->string('type')->default('regular'); // regular, keynote, workshop, etc.
            $table->timestamps();
        });

        Schema::create('presentations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('paper_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('abstract')->nullable();
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->string('speaker_name');
            $table->string('speaker_affiliation')->nullable();
            $table->string('speaker_bio')->nullable();
            $table->string('speaker_photo_path')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('presentations');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('program_books');
    }
};
