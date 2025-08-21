<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{

    public function storeClient(Request $request)
    {
        if (!auth()->check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Vui lòng đăng nhập để đánh giá'
            ], 401);
        }

        $user = auth()->user();

        if ($user->ban_rate) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tài khoản của bạn đã bị cấm đánh giá'
            ], 403);
        }

        $request->validate([
            'rating' => 'required|integer|between:1,5'
        ]);

        $user->rating = $request->rating;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Đã cập nhật đánh giá',
            'rating' => $request->rating
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Rating $rating)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rating $rating)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rating $rating)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rating $rating)
    {
        //
    }
}
