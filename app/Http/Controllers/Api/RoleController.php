<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Role;

class RoleController extends Controller
{
    public function index(){
        $role = Role::get();

        return response()->json([
            'success' => true,
            'message' => 'Tampil data Role berhasil',
            'data' => $role,
        ], 200);
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|min:5'
        ],[
            'required' => 'inputan dengan :attribute harus diisi',
            'min' => 'inputan harus :min karakter.'
        ]);

        Role::create([
            'name' => $request->input('name')
        ]);

        return response([
            'success' => true,
            'message' => 'Tambah Role Berhasil'
        ], 200);
    }

    public function show($id){
        $role = Role::find($id);

        if (!$role) {
            return response([
                'success' => false,
                'message' => 'Data dengan $id tidak ditemukan'
            ], 404);
        }

        return response([
            'success' => true,
            'message' => 'Detail Data Menampilkan detail Role berdasarkan id',
            'data' => $role
        ], 200);
    }

    public function update(Request $request,string $id){
        $request->validate([
            'name' => 'required'
        ],[
            'required' => 'inputan dengan :attribute harus diisi',
        ]);

        $role = Role::find($id);
        $role->name = $request->input('name');
        
        if (!$role) {
            return response([
                'success' => false,
                'message' => 'Data dengan $id tidak ditemukan'
            ], 404);
        }
        
        $role->save();
        return response([
            'success' => true,
            'message' => 'Update Role berhasil'
        ], 201);
    }

    public function destroy($id){
        $role = Role::find($id);
        
        if (!$role) {
            return response([
                'success' => false,
                'message' => 'Data dengan $id tidak ditemukan'
            ], 404);
        }
        $role->delete();

        return response([
            'success' => true,
            'message' => 'berhasil Menghapus Role berdasarkan id'
        ], 201);
    }
}