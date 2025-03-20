<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20|unique:users,phone',
            'password' => 'required|string|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->userService->register($request->all());

        return response()->json($result, $result['success'] ? 201 : 400);
    }

    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|max:20|exists:users,phone',
            'otp' => 'required|string|size:6'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->userService->verifyOtp($request->phone, $request->otp);

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|max:20',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->userService->login($request->all());

        return response()->json($result, $result['success'] ? 200 : 401);
    }

    public function profile(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'user' => $request->user()
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $result = $this->userService->logout($request->user()->id);

        return response()->json($result);
    }
} 