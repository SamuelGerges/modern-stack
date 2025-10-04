<?php

namespace App\Repositories\implementation;

use App\Repositories\IUserRepository;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class UserRepository implements IUserRepository
{
    public function register($data)
    {
        return User::query()->create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => $data['password'],
        ]);

    }


    public function getUserByEmail($email)
    {
        return User::query()->where('email', $email)->first();
    }
}