<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{


    protected $table = 'courses';
    protected $fillable =['title','material_id'];

    public $timestamps = true;
    public function material()
    {
        return $this->belongsTo('App\Models\Material');
    }





}

