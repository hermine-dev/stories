<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoryItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('story_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('story_id');
            $table->enum('type', ['image', 'video']);
            $table->string('mime_type');
            $table->string('original_name');
            $table->string('path_name');

            $table->foreign('story_id')
                ->references('id')->on('stories')
                ->onUpdate('cascade')
                ->onDelete('cascade');

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
        Schema::table('story_items', function (Blueprint $table) {
            $table->dropForeign('story_items_story_id_foreign');
        });

        Schema::dropIfExists('story_items');
    }
}
