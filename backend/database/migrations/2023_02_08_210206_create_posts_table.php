<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->string('author');
            $table->string('image');
            $table->unsignedInteger('type')->default(1)->comment = '0:translate 1:create';
            $table->integer('view');
            $table->longText('description')->nullable();
            $table->char('status')->default('active');
            $table->unsignedInteger('is_done')->default(1)->comment = '0:undone 1:done';
            $table->string('slug')->unique();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
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
};
