<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoogleCalendarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('google_calendars', function (Blueprint $table) {
            $table->id();
            $table->string('Event_id')->nullable();
            $table->string('Meeting_plateform')->nullable();
            $table->string('Meeting_link')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('Organizer')->nullable();
            $table->string('Attendees')->nullable();
            $table->string('Recurrence')->nullable();
            $table->string('Summary')->nullable();
            $table->longText('Description')->nullable();
            $table->string('Starting_time')->nullable();
            $table->string('Ending_time')->nullable();
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
        Schema::dropIfExists('google_calendars');
    }
}
