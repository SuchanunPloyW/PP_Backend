<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DB_user_groupModel extends Model
{
    protected $primaryKey = 'id'; //ตัวชี้ column
    protected $table = 'db_user_group';     //ตัวชี้table
    protected $connection = 'mysql'; //ตัวชี้connection
    protected $guarded = ['id'];  //post
    public $timestamps = false; // no timestamps
    use HasFactory;
}