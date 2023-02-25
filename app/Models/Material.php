<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = [
        'name', 'code','course_id'
    ];

    protected $table="materials";

    public function sections(){
        return $this->hasMany('App\Models\Section');
    }
    public function course(){
        return $this->belongsTo('App\Models\Course');
    }

}
