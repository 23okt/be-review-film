<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profile;
class ProfileController extends Controller
{
    public function storeupdate(Request $request){
        $user = auth()->user();
        $request->validate([
            'age' => 'required|integer',
            'biodata' => 'required|min:5',
            'address' => 'required|min:5'
        ],[
            'required' => 'inputan :attribute harus diisi.',
            'min' => 'inputan :attribute minimal :min karakter.',
            'integer' => 'inputan :attribute harus menggunakan angka.'
        ]);

        $profile = Profile::updateOrCreate(
        ['user_id' => $user->id],
        [
            'biodata' => $request->input('biodata'),
            'age' => $request->input('age'),
            'address' => $request->input('address')
        ]);

        return response([
            'message' => 'Profile berhasil diubah',
        ]);
    }
}