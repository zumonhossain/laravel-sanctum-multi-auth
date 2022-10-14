<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\AdminLoginRequest;
use App\Http\Resources\Admin\AdminAuthResource;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminAuthController extends Controller{
    public function login(AdminLoginRequest $request){
        $admin = Admin::where('phone', $request->phone)->first();
        if (!$admin || !Hash::check($request->password, $admin->password)) {
            throw ValidationException::withMessages([
                'phone' => ['The provided credentials are incorrect.'],
            ]);
        }

        return $this->makeToken($admin);
    }

    public function makeToken($admin){
        $token =  $admin->createToken('admin-token')->plainTextToken;
        return (new AdminAuthResource($admin))
            ->additional(['meta' => [
                'token' => $token,
                'token_type' => 'Bearer',
            ]]);
    }

    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return send_ms('admin logout', true, 200);
    }

    public function user(Request $request){
        return AdminAuthResource::make($request->user());
    }
}
