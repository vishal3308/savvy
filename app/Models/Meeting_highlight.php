<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting_highlight extends Model
{
    use HasFactory;
    protected $table="meeting_highlight";
    protected $primarykey="id";
}
