<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Review\StoreReviewRequest;
use App\Http\Requests\Review\UpdateReviewRequest;

class ReviewController extends Controller
{
    public function __construct(protected ReviewService $reviewService) {}

    /**
     * Display a listing of the resource.
     */
    public function show()
    {
        $reviews = $this->reviewService->show();
        return response()->json([
            'message' => 'Reviews retrieved successfully.',
            'data' => $reviews
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReviewRequest $request){

        $review = $this->reviewService->create($request->validated(), $request->user());
        return response()->json([
            'message' => 'Review added successfully.',
            'data' => $review->load('user')
        ], 201);
    }
    /**
     * Display the specified resource.
     */
    // public function show(string $id)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReviewRequest $request, int $id){

        $review = $this->reviewService->update($id, $request->validated(), $request->user());
        return response()->json([
            'message' => 'Review updated successfully.',
            'data' => $review
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, int $id){

        $this->reviewService->delete($id, $request->user());
        return response()->json([
            'message' => 'Review deleted successfully.',
        ]);
    }
}
