<?php

namespace App\Services;

use App\Models\Ad;
use App\Services\AdService;
use App\Jobs\ProcessAdImages;
use App\Jobs\SendAdConfirmationEmail;

class AdService
{
    /**
     * Create a new class instance.
     */
    public function getAllActiveAds(){

    return Cache::remember('active_ads_most_viewed', now()->addMinutes(10), function () {
        return Ad::with(['category', 'user', 'images', 'reviews'])
            ->withCount('reviews')
            ->where('status', 'active')
            ->take(20)
            ->get();
    });
    }
    public function createAdWithImages(array $data, $user): Ad
    {
        return DB::transaction(function () use ($data, $user) {
            // create Ad
            $ad = Ad::create([
                'title' => $data['title'],
                'description' => $data['description'],
                'price' => $data['price'],
                'category_id' => $data['category_id'],
                'user_id' => $user->id,
                'status' => 'pending',
            ]);

            // upload image if exists
            if (isset($data['images'])) {
                foreach ($data['images'] as $image) {
                    $path = $image->store('ads/images', 'public');

                    $ad->images()->create([
                        'path' => $path,
                    ]);
                }
            }
            Cache::forget('active_ads_most_viewed'); 
            dispatch(new SendAdConfirmationEmail($ad));
            dispatch(new ProcessAdImages($ad));

            return $ad;
        });
    }

    public function updateAdWithImages(int $adId, array $data, $user){

        return DB::transaction(function () use ($adId, $data, $user) {
        $ad = Ad::where('id', $adId)->where('user_id', $user->id)->firstOrFail();

        //update existing data only
        $ad->fill($data);
        $ad->save();

        // delete image if requested
        if (!empty($data['remove_images'])) {
            $ad->images()->whereIn('id', $data['remove_images'])->each(function ($image) {
                $image->delete(); 
            });
        }

        // add image if requested
        if (!empty($data['images'])) {
            foreach ($data['images'] as $image) {
                $path = $image->store('ads/images', 'public');
                $ad->images()->create([
                    'path' => $path,
                ]);
            }
        }

        Cache::forget('active_ads_most_viewed');
        return $ad->refresh();
    });
    }

    public function deleteAd(int $adId, $user){
        DB::transaction(function () use ($adId, $user) {
        $ad = Ad::where('id', $adId)
                ->where('user_id', $user->id) 
                ->firstOrFail();
        $ad->delete();
    });
    }

    public function changeStatus(Ad $ad, string $status){
        $ad->status = $status;
        $ad->save();
        return $ad;
    }
}