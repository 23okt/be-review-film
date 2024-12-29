<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\UserRegistMail;
use App\Mail\GenerateCodeMail;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;
use App\Models\OtpCode;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function register(Request $request){
        $request->validate([
            'name' => 'required|min:5',
            'email' => 'required|email|unique:user,id',
            'password' => 'required|min:8|confirmed',
        ],[
            'required' => 'inputan :attribute harus diisi',
            'min' => 'inputan :attribute minimal :min karakter',
            'email' => 'inputan :attribute harus berformat email',
            'unique' => 'inputan email sudah terdaftar',
            'confirmed' => 'inputan password berbeda'
        ]);

        $user = new User;
        $roleUser = Role::where('name','user')->first();

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->role_id = $roleUser->id;

        $user->save();

        Mail::to($user->email)->send(new UserRegistMail($user));


        return response([
            'success' => true,
            'message' => 'Register Berhasil, Cek Email Secara Berkala'
        ], 200);
    }

    public function login(Request $request){

        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ],[
            'required' => 'inputan :attribute harus diisi',
        ]);

        $credetials = request(['email', 'password']);
        if (!$token = auth()->attempt($credetials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = User::with('role')->where('email', $request->input('email'))->first();

        return response([
            'success' => true,
            'message' => 'Berhasil Login',
            'user' => $user,
            'token' => $token
        ], 200);
    }

    public function getUser(){
        $user = auth()->user();
        if (!$user) {
            return response()->json([
                'message' => 'User not authenticated',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'Profile berhasil ditampilkan',
            'user' => $user,
        ]);
    }

    public function logout(){
        auth()->logout();

        return response()->json([
            'success' => true,
            'message' => 'Logout Berhasil',
        ], 200);
    }

    public function generateOtp(Request $request){
        $request->validate([
            'email' => 'required|email',
        ],[
            'required' => 'inputan :attribute harus diisi',
            'email' => 'inputan :attribute harus berformat email'
        ]);
        $user = User::where('email', $request->input('email'))->first();
        $user->generate_otp();

        Mail::to($user->email)->send(new GenerateCodeMail($user));

        return response()->json([
            'success' => true,
            'message' => 'Kode OTP berhasil dibuat, cek email anda!',
        ], 200);
    }

    public function verifikasi(Request $request){
        $request->validate([
            'otp' => 'required|max:6',
        ],[
            'required' => 'inputan :attribute harus diisi',
            'max' => 'inputan :attribute hanya menampung :max karakter'
        ]);

        $user = auth()->user();
        $otp = OtpCode::where('otp', $request->input('otp'))->where('user_id', $user->id)->first();

        if (!$otp) {
            return response()->json([
                'success' => false,
                'message' => 'Kode OTP anda salah',
            ], 404);
        }

        $now =  Carbon::now();

        if ($now > $otp->valid_until) {
            return response()->json([
                'success' => false,
                'message' => 'Kode OTP sudah kadaluarsa',
            ], 400);
        }


        $user = User::find($otp->user_id);

        $user->email_verified_at = $now;

        $user->save();
        $otp->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Verifikasi Berhasil',
        ], 200);
    }
}