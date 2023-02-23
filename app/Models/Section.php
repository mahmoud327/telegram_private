<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $fillable = [
        'name',
        'material_id',
        'link_whatsup'

    ];
    public function material()
    {
        return $this->belongsTo('App\Models\Material');
    }
}
