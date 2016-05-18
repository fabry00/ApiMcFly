<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('notes', function (Blueprint $table) {
            $table->increments('id');
            $table->string("text");
            $table->boolean("public");
            
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')
                    ->onUpdate('cascade')->onDelete('cascade');
            
            $table->timestamps();
        });

        // Create table for associating favorites to users (Many-to-Many)
        Schema::create('favorite_notes', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('note_id')->unsigned();

            $table->foreign('user_id')->references('id')->on('users')
                    ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('note_id')->references('id')->on('notes')
                    ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['user_id', 'note_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('favorite_notes');
        Schema::drop('notes');
    }

}
