<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    use HasFactory;

    protected $table = "clubs";
    protected $fillable = ["name","creation_date","owner","value"];
    protected $primaryKey = "id";
    public $timestamps = false;
}
