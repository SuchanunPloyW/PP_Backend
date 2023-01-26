<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DB_GradecarModel extends Model
{
    protected $primaryKey = 'id'; //ตัวชี้ column
    protected $table = 'db_gradecar';     //ตัวชี้table
    protected $connection = 'mysql2'; //ตัวชี้connection
    protected $guarded = ['id'];  //post
    public $timestamps = false; // no timestamps
    use HasFactory;
}