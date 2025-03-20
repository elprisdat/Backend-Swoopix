<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Models\Voucher;
use Carbon\Carbon;

class VoucherSeeder extends Seeder
{
    public function run(): void
    {
        $vouchers = [
            [
                'code' => 'WELCOME25',
                'type' => 'percentage',
                'value' => 25,
                'min_order' => 50000,
                'max_discount' => 25000,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(1),
                'is_active' => true
            ],
            [
                'code' => 'HEMAT10K',
                'type' => 'fixed',
                'value' => 10000,
                'min_order' => 30000,
                'max_discount' => null,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(1),
                'is_active' => true
            ],
            [
                'code' => 'DISKON50',
                'type' => 'percentage',
                'value' => 50,
                'min_order' => 100000,
                'max_discount' => 50000,
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