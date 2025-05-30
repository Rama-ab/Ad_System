<?php

namespace App\Models;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Ad extends Model
{
    protected $fillable = [
        'title',
        'description',
        'price',
        'user_id', 
        'category_id', 
        'status'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function reviews(){
        return $this->hasMany(Review::class);
    }

    public function images(){
        return $this->morphMany(Image::class, 'imageable');
    }

    public function mainImage(){
        return $this->morphMany(Image::class, 'imageable')->ofMany('id', 'min');
    }

    //scope
    public function scopeActive(Builder $query){
        return $query->where('status', 'active');
    }

    public function scopeUserAds(Builder $query, int $userId){
        return $query->where('user_id', $userId);
    }

    public function scopeWithCategory(Builder $query, int $categoryId){
        return $query->where('category_id', $categoryId);
    }

    //accessor
    protected function formattedPrice(){
        return Attribute::get(fn () => number_format($this->price, 2) . ' $');
    }

    protected function createdAtFormatted(){
        return Attribute::get(fn () => $this->created_at->format('Y-m-d H:i'));
    }

    //mutator
    protected function title(){
        return Attribute::set(fn ($value) => strtolower($value));
    }


    public function wasJustCreated(){
        return $this->wasRecentlyCreated;
    }

    public function isModified(){
        return $this->isDirty();
    }
}
