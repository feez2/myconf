<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('paper_authors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paper_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('affiliation')->nullable();
            $table->boolean('is_corresponding')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();

            // Indexes
            $table->index(['paper_id', 'order']);
            $table->index(['user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('paper_authors');
    }
}; 