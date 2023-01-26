<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DB_CustomerModel extends Model
{
    protected $primaryKey = 'id'; //ตัวชี้ column
    protected $table = 'db_customer';     //ตัวชี้table
    protected $connection = 'mysql2'; //ตัวชี้connection
    protected $guarded = ['id'];  //post
    public $timestamps = false; // no timestamps
    use HasFactory;

    public function car()
    {
        return $this->belongsTo('App\Models\DB_CarModel', 'car')

            ->select(['id', 'detail']);
    }
    public function color_car()
    {
        return $this->belongsTo('App\Models\DB_ColorModel', 'color_car')
            ->select(['id', 'detail']);
    }
    public function grade_car()
    {
        return $this->belongsTo('App\Models\DB_GradecarModel', 'grade_car')
            ->select(['id', 'detail']);
    }
    public function campaign()
    {
        // join table json
        return $this->belongsTo('App\Models\DB_CampaignModel', 'campaign')
            ->select(['id', 'detail']);
    }
}