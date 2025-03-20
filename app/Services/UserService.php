<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService extends BaseService
{
    protected $userRepository;

    public function __construct(UserRepository $repository)
    {
        parent::__construct($repository);
        $this->userRepository = $repository;
    }

    public function register(array $data): array
    {
        // Validate if user exists
        if ($this->userRepository->findByPhone($data['phone'])) {
            return [
                'success' => false,
                'message' => 'Nomor telepon sudah terdaftar'
            ];
        }

        // Generate OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Create user
        $user = $this->userRepository->create([
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'otp' => $otp,
            'otp_expired_at' => now()->addMinutes(5),
            'points' => 0,
            'is_verified' => false
        ]);

        if (!$user) {
            return [
                'success' => false,
                'message' => 'Gagal membuat user'
            ];
        }

        // TODO: Send OTP via SMS/WhatsApp

        return [
            'success' => true,
            'message' => 'Berhasil mendaftar, silahkan verifikasi OTP',
            'data' => [
                'user' => $user,
                'otp' => $otp // In production, remove this
            ]
        ];
    }

    public function verifyOtp(string $phone, string $otp): array
    {
        if (!$this->userRepository->verifyOtp($phone, $otp)) {
            return [
                'success' => false,
                'message' => 'OTP tidak valid atau sudah kadaluarsa'
            ];
        }

        $this->userRepository->markAsVerified($phone);

        return [
            'success' => true,
            'message' => 'Verifikasi berhasil'
        ];
    }

    public function login(array $credentials): array
    {
        $user = $this->userRepository->findByPhone($credentials['phone']);

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return [
                'success' => false,
                'message' => 'Kredensial tidak valid'
            ];
        }

        if (!$user->is_verified) {
            return [
                'success' => false,
                'message' => 'Akun belum terverifikasi'
            ];
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'user' => $user,
                'token' => $token
            ]
        ];
    }

    public function updatePoints(string $userId, int $points): array
    {
        if (!$this->userRepository->updatePoints($userId, $points)) {
            return [
                'success' => false,
                'message' => 'Gagal mengupdate poin'
            ];
        }

        return [
            'success' => true,
            'message' => 'Berhasil mengupdate poin'
        ];
    }

    public function logout(string $userId): array
    {
        $user = $this->userRepository->find($userId);
        
        if (!$user) {
            return [
                'success' => false,
                'message' => 'User tidak ditemukan'
            ];
        }

        $user->tokens()->delete();

        return [
            'success' => true,
            'message' => 'Berhasil logout'
        ];
    }
} 