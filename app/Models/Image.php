<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    protected $fillable = ['path',];

    public function imageable(){
        return $this->morphTo();
    }

    public function mainImage(){
    return $this->morphMany(Image::class, 'imageable')->ofMany('id', 'min');
    }


    //accessor
    public function getUrlAttribute() {
        return Storage::url($this->path);
    }

    protected static function booted()
    {
        static::deleting(function (Image $image) {
            if (Storage::exists($image->path)) {
                Storage::delete($image->path);
            }
        });
    }
}