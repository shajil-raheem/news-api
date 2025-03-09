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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('source', 100)->index();
            $table->string('category', 100)->index();
            $table->string('author', 100)->index();
            $table->date('date')->index();
            $table->string('title', 200);
            $table->string('description', 500);
            $table->string('content_url');
            $table->string('id_from_source', 500)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
