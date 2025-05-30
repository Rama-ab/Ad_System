<?php

namespace App\Services;

use App\Models\Ad;
use App\Models\User;
use App\Models\Review;

class ReviewService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function create(array $data, User $user){
        
        return Review::create([
            'user_id' => $user->id,
            'ad_id' => $data['ad_id'],
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
        ]);
    }

    public function update(int $id, array $data, User $user){

        $review = Review::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();
        $review->update($data);

        return $review;
    }

    public function show(Request $request, int $adId){
        $ad = Ad::findOrFail($adId);
        $reviews = $ad->reviews()
            ->with('user')
            ->latest()
            ->paginate(10);
        return $reviews;
    }

    public function delete(int $id, User $user){

        $review = Review::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $review->delete();
    }
}
