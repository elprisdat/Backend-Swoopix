<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends BaseRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }

    public function findByPhone(string $phone): ?User
    {
        return $this->model->where('phone', $phone)->first();
    }

    public function verifyOtp(string $phone, string $otp): bool
    {
        $user = $this->findByPhone($phone);
        if (!$user) {
            return false;
        }

        return $user->otp === $otp && $user->otp_expired_at > now();
    }

    public function markAsVerified(string $phone): bool
    {
        $user = $this->findByPhone($phone);
        if (!$user) {
            return false;
        }

        return $user->update([
            'is_verified' => true,
            'otp' => null,
            'otp_expired_at' => null
        ]);
    }

    public function updatePoints(string $userId, int $points): bool
    {
        $user = $this->find($userId);
        if (!$user) {
            return false;
        }

        return $user->update([
            'points' => $user->points + $points
        ]);
    }
} 