<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Seller\SellerLoginRequest;
use App\Http\Resources\Seller\SellerAuthResource;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use App\Models\Seller;

class SellerAuthController extends Controller{
    public function login(SellerLoginRequest $request){
        $seller = Seller::where('phone', $request->phone)->first();
        if (!$seller || !Hash::check($request->password, $seller->password)) {
            throw ValidationException::withMessages([
                'phone' => ['The provided credentials are incorrect.'],
            ]);
        }

        return $this->makeToken($seller);
    }

    public function makeToken($seller){
        $token =  $seller->createToken('seller-token')->plainTextToken;
        return (new SellerAuthResource($seller))
            ->additional(['meta' => [
                'token' => $token,
                'token_type' => 'Bearer',
            ]]);
    }

    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return send_ms('seller logout', true, 200);
    }

    public function user(Request $request){
        return SellerAuthResource::make($request->user());
    }
}
