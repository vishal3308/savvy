<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting_transcript extends Model
{
    use HasFactory;
    protected $table="meeting_transcript";
    protected $primarykey="id";
}
