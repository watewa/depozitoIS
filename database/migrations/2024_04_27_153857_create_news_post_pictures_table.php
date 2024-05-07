<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('news_post_pictures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('news_post_id');
            $table->foreign('news_post_id')->references('id')->on('news_posts')->onDelete('cascade');
            $table->string('picture');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_post_pictures');
    }
};
