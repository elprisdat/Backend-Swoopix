<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Voucher;
use Carbon\Carbon;

class VoucherSeeder extends Seeder
{
    public function run(): void
    {
        $vouchers = [
            [
                'code' => 'WELCOME25',
                'discount_type' => 'percentage',
                'discount_value' => 25,
                'minimum_purchase' => 50000,
                'max_discount' => 25000,
                'max_usage' => 100,
                'used_count' => 0,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(1),
                'is_active' => true
            ],
            [
                'code' => 'HEMAT10K',
                'discount_type' => 'fixed',
                'discount_value' => 10000,
                'minimum_purchase' => 30000,
                'max_discount' => null,
                'max_usage' => 50,
                'used_count' => 0,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(1),
                'is_active' => true
            ],
            [
                'code' => 'DISKON50',
                'discount_type' => 'percentage',
                'discount_value' => 50,
                'minimum_purchase' => 100000,
                'max_discount' => 50000,
                'max_usage' => 25,
                'used_count' => 0,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(7),
                'is_active' => true
            ]
        ];

        foreach ($vouchers as $voucher) {
            Voucher::create($voucher);
        }
    }
} 