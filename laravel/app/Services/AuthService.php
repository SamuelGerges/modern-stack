<?php

namespace App\Services;

use App\Repositories\IUserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    protected $userRepository;
    /**
     * @param $authRepository
     */
    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register($data)
    {
        return $this->userRepository->register($data);
    }


    public function login($credentials)
    {

        $user = $this->userRepository->getUserByEmail($credentials['email']);
        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return false; // invalid credentials
        }

        // create sanctum token
        $token = $user->createToken('api_token')->plainTextToken;
        return [
            'user'  => $user,
            'token' => $token,
        ];
    }

    public function logout(): bool
    {
        $user = Auth::user();

        if ($user && $user->currentAccessToken()) {
            $user->currentAccessToken()->delete();
            return true;
        }

        return false;
    }

}