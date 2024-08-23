<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiController;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\User\PutUserRequest;
use App\Http\Requests\User\PasswordRequest;
use App\Http\Requests\User\StoreUserRequest;



class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->respond(User::with('roles','permissions')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $roles = $request->roles;
        $permissions = $request->permissions;

        $user = User::create($request->validated());

        if (!empty($roles)) {
            $user->syncRoles($roles);
        }
        if (!empty($permissions)) {
            $user->syncPermissions($permissions);
        }

        return $this->respondCreated($user->load('roles', 'permissions'));
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
    public function update(PutUserRequest $request, User $user)
    {
        $roles = $request->roles;
        $permissions = $request->permissions;

        if ($request->password) {
            $user->update($request->validated());
        } else {
            $user->update($request->only(['name', 'email']));
        }

        $user->syncRoles($roles);
        $user->syncPermissions($permissions);

        return $this->respond($user->load('roles', 'permissions'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return $this->respondSuccess();
    }

    public function getOptions()
    {
        $data = [
            'roles' => Role::all(),
            'permissions' => Permission::all(),
        ];
        return $this->respond($data);
    }

    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials)) {
            $user = User::where('email', $request->email)->first()->load('roles', 'permissions','employee.position');
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
