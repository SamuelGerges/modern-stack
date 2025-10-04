<?php

namespace App\Repositories;

interface IUserRepository
{
    public function register($data);

    public function getUserByEmail($email);

}