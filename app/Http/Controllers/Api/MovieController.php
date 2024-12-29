<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Movie;

class MovieController extends Controller
{
    public function __construct(){
        $this->middleware(['auth:api', 'checkrole'])->except('index','show');
    }

    public function index(){
        $movie = Movie::get();

        return response()->json([
            'success' => true,
            'message' => 'Tampil data Movie Berhasil',
            'data' => $movie
        ], 200);
    }

    public function store(Request $request){
        $request->validate([
            'title' => 'required|min:1',
            'summary' => 'required|min:5',
            'poster' => 'image|mimes:jpeg,png,jpg|max:2048',
            'genre_id' => 'required|exists:genres,id',
            'year' => 'required'
        ],[
            'required' => 'inputan :attribute harus diisi.',
            'min' => 'inputan :attribute minimal :min karakter.',
            'max' => 'file tidak bisa mengakses gambar lebih dari :max',
            'mimes' => 'file harus berupa jpeg,png,jpg',
            'exists' => 'Genre film tidak ditemukan'
        ]);
        
        $movie = new Movie();
        if ($request->hasFile('poster')) {
            $uploadedFileUrl = cloudinary()->upload($request->file('poster')->getRealPath(), [
                'folder' => 'image',
            ])->getSecurePath();

            $movie->poster = $uploadedFileUrl;
        }

        $movie->title = $request->input('title');
        $movie->summary = $request->input('summary');
        $movie->year = $request->input('year');
        $movie->genre_id = $request->input('genre_id');

        $movie->save();

        return response([
            'success' => true,
            'message' => 'Tambah Movie berhasil'
        ], 201);
    }

    public function show($id){
        $movie = Movie::with(['genre','list_cast','list_reviews'])->find($id);

        if (!$movie) {
            return response([
                'success' => false,
                'message' => 'Movie tidak ditemukan'
            ], 404);
        }

        return response([
            'success' => true,
            'message' => 'Detail Data Movie',
            'data' => $movie
        ], 200);
    }

    public function update(Request $request,string $id){
        $request->validate([
            'title' => 'required|min:1',
            'summary' => 'required',
            'poster' => 'image|mimes:jpeg,png,jpg|max:2048',
            'genre_id' => 'required|exists:genres,id',
            'year' => 'required'
        ],[
            'required' => 'inputan :attribute harus diisi.',
            'min' => 'inputan :attribute minimal :min karakter.',
            'max' => 'file tidak bisa mengakses gambar lebih dari :max',
            'mimes' => 'file harus berupa jpeg,png,jpg',
            'exists' => 'Genre film tidak ditemukan'
        ]);

        $movie = Movie::find($id);

        if ($request->hasFile('poster')) {
            $uploadedFileUrl = cloudinary()->upload($request->file('poster')->getRealPath(), [
                'folder' => 'image',
            ])->getSecurePath();

            $movie->poster = $uploadedFileUrl;
        }
        
        if (!$movie) {
            return response([
                'success' => false,
                'message' => 'Movie tidak ditemukan'
            ], 404);
        }

        $movie->title = $request->input('title');
        $movie->summary = $request->input('summary');
        $movie->year = $request->input('year');
        $movie->genre_id = $request->input('genre_id');
        $movie->save();

        return response([
            'success' => true,
            'message' => 'Update Movie berhasil'
        ], 201);
    }

    public function destroy(string $id){
        $movie = Movie::find($id);

        if(!$movie){
            return response([
                'success' => false,
                'message' => 'Movie tidak ditemukan'
            ], 404);
        }

        $movie->delete();

        return response([
            'success' => true,
            'message' => 'Berhasil menghapus Movie'
        ], 201);
    }
}