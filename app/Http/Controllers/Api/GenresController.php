<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Genres;

class GenresController extends Controller
{

    public function __construct(){
        $this->middleware(['auth:api', 'checkrole'])->except('index','show');
    }

    public function index(){
        $genre = Genres::get();

        return response()->json([
            'success' => true,
            'message' => 'Tampil data genre berhasil',
            'data' => $genre
        ], 200);
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|min:1',
        ],[
            'required' => 'inputan :attribute harus diisi.',
            'min' => 'inputan :attribute minimal :min karakter.'
        ]);

        Genres::create([
            'name' => $request->input('name')
        ]);

        return response([
            'success' => true,
            'message' => 'Tambah genre berhasil'
        ], 200);
    }
    
    public function show($id){
        $genre = Genres::with('genre')->find($id);

        if (!$genre) {
            return response([
                'success' => false,
                'message' => 'Data dengan $id tidak ditemukan'
            ], 404);
        }

        return response([
            'success' => true,
            'message' => 'Detail Data Genre',
            'data' => $genre
        ], 200);
    }

    public function update(Request $request, string $id){
        $request->validate([
            'name' => 'required|min:1',
        ],[
            'required' => 'inputan :attribute harus diisi.',
            'min' => 'inputan :attribute minimal :min karakter.'
        ]);

        $genre = Genres::find($id);
        $genre->name = $request->input('name');
        
        if (!$genre) {
            return response([
                'message' => 'Data dengan $id tidak ditemukan'
            ], 404);
        }

        $genre->save();
        return response ([
            'message' => 'Update Genre Berhasil'
        ], 201);
    }

    public function destroy($id){
        $genre = Genres::find($id);
        
        if (!$genre) {
            return response([
                'message' => 'Data dengan $id tidak ditemukan'
            ], 404);
        }
        $genre->delete();

        return response([
            'message' => 'berhasil Menghapus Genre'
        ], 201);
    }
}