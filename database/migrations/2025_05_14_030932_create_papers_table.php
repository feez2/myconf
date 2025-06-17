<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('papers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conference_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('abstract');
            $table->string('keywords');
            $table->string('file_path');
            $table->string('status')->default('submitted'); // submitted, under_review, accepted, rejected
            $table->text('decision_comments')->nullable();
            $table->timestamps();

            // Indexes for performance
            $table->index(['conference_id', 'status']);
            $table->index('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('papers');
    }
};
