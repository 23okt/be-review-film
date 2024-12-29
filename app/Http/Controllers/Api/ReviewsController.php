<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reviews;

class ReviewsController extends Controller
{
    public function storeupdate(Request $request){
        $user = auth()->user();
        $request->validate([
            'rating' => 'required|integer',
            'critic' => 'required|min:5',
            'movie_id' => 'required|exists:movie,id'
        ],[
            'required' => 'inputan :attribute harus diisi.',
            'min' => 'inputan :attribute minimal :min karakter.',
            'integer' => 'inputan :attribute harus menggunakan angka.',
            'exists' => 'inputan :attribute tidak ditemukan di table movie'
        ]);

        $review = Reviews::updateOrCreate(
        ['user_id' => $user->id],
        [
            'critic' => $request->input('critic'),
            'rating' => $request->input('rating'),
            'movie_id' => $request->input('movie_id')
        ]);

        return response([
            'message' => 'Review berhasil diubah',
        ], 201);
    }
}