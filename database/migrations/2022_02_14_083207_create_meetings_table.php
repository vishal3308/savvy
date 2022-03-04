<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('meeting_owner_id')->nullable();
            $table->foreign('meeting_owner_id')->references('id')->on('users');
            
            $table->string('external_meeting_id')->nullable();
            $table->integer('external_meeting_provider_name')->nullable();
            $table->string('external_meeting_name')->nullable();
            $table->timestamps();
            $table->integer('status')->nullable();
            // $table->integer('highlight_id')->nullable();

            $table->unsignedBigInteger('meeting_participants_id')->nullable();
            $table->foreign('meeting_participants_id')->references('id')->on('meeting_participants');

            $table->integer('formatted_summary_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meetings');
    }
}
