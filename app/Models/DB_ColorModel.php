<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DB_ColorModel extends Model
{
    protected $primaryKey = 'id'; //ตัวชี้ column
    protected $table = 'db_color';     //ตัวชี้table
    protected $connection = 'mysql2'; //ตัวชี้connection
    protected $guarded = ['id'];  //post
    public $timestamps = false; // no timestamps
    use HasFactory;

    /*  public function color_car()
    {
        return $this->belongsTo('App\Models\DB_ColorModel', 'color_car')
            ->select(['id', 'detail']);
    } */
}