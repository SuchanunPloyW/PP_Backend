<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DB_CarModel extends Model
{
    protected $primaryKey = 'id'; //ตัวชี้ column
    protected $connection = 'mysql2'; //ตัวชี้connection
    protected $table = 'db_car';     //ตัวชี้table
    protected $guarded = ['id'];  //post
    public $timestamps = false; // no timestamps
    use HasFactory;
}