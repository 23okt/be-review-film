<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Casts;

class CastsController extends Controller
{

    public function __construct(){
        $this->middleware(['auth:api', 'checkrole'])->except('index','show');
    }

    public function index(){
        $cast = Casts::get();

        return response()->json([
            'success' => true,
            'message' => 'Tampil data cast berhasil',
            'data' => $cast
        ], 200);
    }


    public function store(Request $request){
        $request->validate([
            'name' => 'required|min:1',
            'age' => 'required|integer',
            'bio' => 'required|min:5'
        ],[
            'required' => 'inputan :attribute harus diisi.',
            'min' => 'inputan :attribute minimal :min karakter.'
        ]);

        Casts::create([
            'name' => $request->input('name'),
            'age' => $request->input('age'),
            'bio' => $request->input('bio')
        ]);

        return response([
            'success' => true,
            'message' => 'Tambah Cast berhasil'
        ], 200);
    }

    public function show($id){
        $casts = Casts::find($id);
        
        if (!$casts) {
            return response([
                'message' => 'Data dengan $id tidak ditemukan'
            ], 404);
        }

        return response([
            'message' => 'Detail Data Cast',
            'data' => $casts
        ], 200);
    }

    
    public function update(Request $request, string $id){
        $request->validate([
            'name' => 'required|min:1',
            'age' => 'required|integer',
            'bio' => 'required|min:5'
        ],[
            'required' => 'inputan :attribute harus diisi.',
            'min' => 'inputan :attribute minimal :min karakter.'
        ]);

        $casts = Casts::find($id);
        $casts->name = $request->input('name');
        $casts->age = $request->input('age');
        $casts->bio = $request->input('bio');

        if (!$casts) {
            return response([
                'message' => 'Data dengan $id tidak ditemukan'
            ], 404);
        }
        
        $casts->save();
        return response([
            'message' => 'Update Cast berhasil'
        ], 201);

    }

    public function destroy($id){
        $casts = Casts::find($id);
        
        if (!$casts) {
            return response([
                'message' => 'Data dengan $id tidak ditemukan'
            ], 404);
        }
        $casts->delete();

        return response([
            'message' => 'berhasil Menghapus Cast'
        ], 201);
    }
}