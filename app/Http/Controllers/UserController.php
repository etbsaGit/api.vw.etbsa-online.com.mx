<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiController;
use App\Http\Requests\User\PasswordRequest;
use App\Http\Requests\User\StoreUserRequest;



class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->validated());
        return $this->respondCreated($user);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return $this->respond($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }

    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials)) {
            $user = User::where('email', $request->email)->first();
            $token = $user->createToken('myapptoken')->plainTextToken;
            $data = [
                'user' => $user,
                'token' => $token
            ];
            return $this->respond($data);
        }
        return $this->respondUnauthorized();
    }

    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->tokens()->delete();
            return $this->respondSuccess();
        } else {
            return $this->respondUnauthorized();
        }
    }

    public function changePassword(PasswordRequest $request)
    {
        $user = Auth::user();
        if (password_verify($request->old_password, $user->password)) {
            $user->update($request->only(['password']));
            return $this->respondSuccess();
        }
        return $this->respondForbidden();
    }
}
