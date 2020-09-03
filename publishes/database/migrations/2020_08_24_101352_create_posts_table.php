<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->text('title');
            $table->text('seo_title')->nullable();
            $table->text('content');
            $table->text('excerpt')->nullable();
            $table->text('snippet');
            $table->text('slug');
            $table->text('cover')->nullable();
            $table->text('thumbnail')->nullable();
            $table->enum('status', ['published', 'draft'])->default('published');
            $table->integer('views')->default(0);
            $table->integer('category_id')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
