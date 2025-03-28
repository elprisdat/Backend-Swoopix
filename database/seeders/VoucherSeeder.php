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
            ],
            [
                'code' => 'NEWUSER30',
                'discount_type' => 'percentage',
                'discount_value' => 30,
                'minimum_purchase' => 40000,
                'max_discount' => 30000,
                'max_usage' => 200,
                'used_count' => 0,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(2),
                'is_active' => true
            ],
            [
                'code' => 'HEMAT20K',
                'discount_type' => 'fixed',
                'discount_value' => 20000,
                'minimum_purchase' => 50000,
                'max_discount' => null,
                'max_usage' => 75,
                'used_count' => 0,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addWeeks(2),
                'is_active' => true
            ],
            [
                'code' => 'WEEKEND40',
                'discount_type' => 'percentage',
                'discount_value' => 40,
                'minimum_purchase' => 75000,
                'max_discount' => 40000,
                'max_usage' => 100,
                'used_count' => 0,
                'start_date' => Carbon::now()->startOfWeek(),
                'end_date' => Carbon::now()->endOfWeek(),
                'is_active' => true
            ],
            [
                'code' => 'LUNCH15K',
                'discount_type' => 'fixed',
                'discount_value' => 15000,
                'minimum_purchase' => 35000,
                'max_discount' => null,
                'max_usage' => 150,
                'used_count' => 0,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(14),
                'is_active' => true
            ],
            [
                'code' => 'PAYDAY35',
                'discount_type' => 'percentage',
                'discount_value' => 35,
                'minimum_purchase' => 60000,
                'max_discount' => 35000,
                'max_usage' => 80,
                'used_count' => 0,
                'start_date' => Carbon::now()->startOfMonth(),
                'end_date' => Carbon::now()->endOfMonth(),
                'is_active' => true
            ],
            [
                'code' => 'SPESIAL25K',
                'discount_type' => 'fixed',
                'discount_value' => 25000,
                'minimum_purchase' => 70000,
                'max_discount' => null,
                'max_usage' => 50,
                'used_count' => 0,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addWeeks(3),
                'is_active' => true
            ],
            [
                'code' => 'FLASH60',
                'discount_type' => 'percentage',
                'discount_value' => 60,
                'minimum_purchase' => 150000,
                'max_discount' => 75000,
                'max_usage' => 20,
                'used_count' => 0,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addHours(24),
                'is_active' => true
            ]
        ];

        foreach ($vouchers as $voucher) {
            Voucher::create($voucher);
        }
    }
} 