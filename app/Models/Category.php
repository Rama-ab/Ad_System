<?php

namespace App\Models;

use App\Models\Ad;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'slug'];

    public function ads(){
        return $this->hasMany(Ad::class);
    }
}
