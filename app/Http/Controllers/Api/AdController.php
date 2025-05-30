<?php

namespace App\Http\Controllers\Api;

use App\Services\AdService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ad\StoreAdRequest;
use App\Http\Requests\Ad\UpdateAdRequest;

class AdController extends Controller
{
    protected AdService $adService;
    public function __construct(AdService $adService)
    {
        $this->adService = $adService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(){

    $ads = $this->adService->getAllActiveAds();
    return response()->json([
        'message' => 'Ads retrieved successfully.',
        'data' => $ads,
    ]);
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAdRequest $request){

        $ad = $this->adService->createAdWithImages($request->validated(), $request->user());
        return response()->json([
            'message' => 'Ad created successfully.',
            'data' => $ad->load(['category', 'images']), 
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    
    public function update(UpdateAdRequest $request, int $adId){

    $ad = $this->adService->updateAdWithImages($adId, $request->validated(), $request->user());
    return response()->json([
        'message' => 'Ad updated successfully.',
        'data' => $ad->load(['category', 'images']),
    ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $adId, Request $request)
{
        $this->adService->deleteAd($adId, $request->user());
        return response()->json([
            'message' => 'Ad deleted successfully.'
        ]);
    }

    public function changeStatus(Ad $ad){
        $this->authorize('change', $ad);

        request()->validate(['status' => 'required|in:active,rejected']);
        $ad = $this->adService->changeStatus($ad, request('status'));
        return response()->json([
            'message' => 'Ad status updated.',
            'data' => $ad
        ]);
    }
}
