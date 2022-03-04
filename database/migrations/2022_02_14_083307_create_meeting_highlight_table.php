<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeetingHighlightTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meeting_highlight', function (Blueprint $table) {
            $table->id();
            $table->integer('moment_type')->nullable();
            $table->unsignedBigInteger('transcript_text_id')->nullable();
            $table->foreign('transcript_text_id')->references('id')->on('meeting_transcript');

            $table->unsignedBigInteger('transcript_text_preceeding_id')->nullable();
            $table->foreign('transcript_text_preceeding_id')->references('id')->on('meeting_transcript');
            
            $table->unsignedBigInteger('transcript_text_post_id')->nullable();
            $table->foreign('transcript_text_post_id')->references('id')->on('meeting_transcript');

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
        Schema::dropIfExists('meeting_highlight');
    }
}
