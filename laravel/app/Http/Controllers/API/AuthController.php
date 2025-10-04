<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponsesHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\Auth\LoginRequest;
use App\Http\Requests\API\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Symfony\Component\HttpFoundation\Response;


class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $user = $this->authService->register($data);

        return ResponsesHelper::returnData(new UserResource($user),
            'User registered successfully',
        Response::HTTP_CREATED);


    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        $user        = $this->authService->login($credentials);

        if(!$user)
        {
            return ResponsesHelper::returnError(Response::HTTP_UNAUTHORIZED, 'The provided credentials do not match our records');
        }

        return ResponsesHelper::returnData($user , 'User Login Successfully',Response::HTTP_OK);
    }

    public function logout()
    {
        $result = $this->authService->logout();

        if ($result) {
            return  ResponsesHelper::returnSuccessMessage(
                Response::HTTP_OK,
                'User logged out successfully'
            );
        }

        return  ResponsesHelper::returnSuccessMessage(
            Response::HTTP_BAD_REQUEST,
            'Unable to logout',
        );
    }

}
