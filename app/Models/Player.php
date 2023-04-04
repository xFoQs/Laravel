<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $table = "players";
    protected $fillable = ["name","surname","birthday","position","club"];
    protected $primaryKey = "id";
    public $timestamps = false;
}
