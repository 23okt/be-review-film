<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CastMovie;

class CastMovieController extends Controller
{

    public function __construct(){
        $this->middleware(['auth:api', 'checkrole'])->except('index','show');
    }

    public function index(){
        $cast_movie = CastMovie::get();

        return response()->json([
            'success' => true,
            'message' => 'Tampil data cast berhasil',
            'data' => $cast_movie
        ], 200);
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|min:1',
            'cast_id' => 'required|exists:casts,id',
            'movie_id' => 'required|exists:movie,id'
        ],[
            'required' => 'inputan :attribute harus diisi.',
            'min' => 'inputan :attribute minimal :min karakter.',
            'exists' => 'inputan :attribute tidak tersedia.'
        ]);

        CastMovie::create([
            'name' => $request->input('name'),
            'cast_id' => $request->input('cast_id'),
            'movie_id' => $request->input('movie_id')
        ]);

        return response([
            'success' => true,
            'message' => 'Tambah Cast Movie berhasil'
        ], 200);
    }

    public function show($id){
        $cast_movie = CastMovie::find($id);
        
        if (!$cast_movie) {
            return response([
                'message' => 'Data dengan $id tidak ditemukan'
            ], 404);
        }

        return response([
            'message' => 'Berhasil Tampil cast Movie',
            'data' => $cast_movie
        ], 200);
    }

    public function update(Request $request, string $id){
        $request->validate([
            'name' => 'required|min:1',
            'cast_id' => 'required|exists:casts,id',
            'movie_id' => 'required|exists:movie,id'
        ],[
            'required' => 'inputan :attribute harus diisi.',
            'min' => 'inputan :attribute minimal :min karakter.',
            'exists' => 'inputan :attribute tidak tersedia.'
        ]);

        $cast_movie = CastMovie::find($id);
        $cast_movie->name = $request->input('name');
        $cast_movie->cast_id = $request->input('cast_id');
        $cast_movie->movie_id = $request->input('movie_id');

        if (!$cast_movie) {
            return response([
                'message' => 'Data dengan $id tidak ditemukan'
            ], 404);
        }
        
        $cast_movie->save();
        return response([
            'message' => 'Berhasil Update cast Movie'
        ], 201);
    }

    public function destroy($id){
        $cast_movie = CastMovie::find($id);
        
        if (!$cast_movie) {
            return response([
                'message' => 'Data dengan $id tidak ditemukan'
            ], 404);
        }
        $cast_movie->delete();

        return response([
            'message' => 'Berhasil Delete cast Movie'
        ], 201);
    }
}