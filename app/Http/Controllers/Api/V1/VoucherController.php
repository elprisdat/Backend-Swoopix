<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::all()->map(function ($voucher) {
            return [
                'id' => $voucher->id,
                'code' => $voucher->code,
                'type' => $voucher->discount_type,
                'value' => $voucher->discount_value,
                'min_order' => $voucher->minimum_purchase,
                'max_discount' => $voucher->max_discount,
                'start_date' => $voucher->start_date,
                'end_date' => $voucher->end_date,
                'is_active' => $voucher->is_active,
                'created_at' => $voucher->created_at,
                'updated_at' => $voucher->updated_at
            ];
        })->toArray();
        
        return response()->json([
            'success' => true,
            'data' => [
                'vouchers' => $vouchers
            ]
        ]);
    }

    public function active()
    {
        $vouchers = Voucher::where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->get()
            ->map(function ($voucher) {
                return [
                    'id' => $voucher->id,
                    'code' => $voucher->code,
                    'type' => $voucher->discount_type,
                    'value' => $voucher->discount_value,
                    'min_order' => $voucher->minimum_purchase,
                    'max_discount' => $voucher->max_discount,
                    'start_date' => $voucher->start_date,
                    'end_date' => $voucher->end_date,
                    'is_active' => $voucher->is_active,
                    'created_at' => $voucher->created_at,
                    'updated_at' => $voucher->updated_at
                ];
            })->toArray();

        return response()->json([
            'success' => true,
            'data' => [
                'vouchers' => $vouchers
            ]
        ]);
    }

    public function show($id)
    {
        $voucher = Voucher::find($id);
        if (!$voucher) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher tidak ditemukan'
            ], 404);
        }

        $formattedVoucher = [
            'id' => $voucher->id,
            'code' => $voucher->code,
            'type' => $voucher->discount_type,
            'value' => $voucher->discount_value,
            'min_order' => $voucher->minimum_purchase,
            'max_discount' => $voucher->max_discount,
            'start_date' => $voucher->start_date,
            'end_date' => $voucher->end_date,
            'is_active' => $voucher->is_active,
            'created_at' => $voucher->created_at,
            'updated_at' => $voucher->updated_at
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'voucher' => $formattedVoucher
            ]
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|unique:vouchers,code',
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'min_order' => 'required|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = [
            'code' => $request->code,
            'discount_type' => $request->type,
            'discount_value' => $request->value,
            'minimum_purchase' => $request->min_order,
            'max_discount' => $request->max_discount,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->is_active ?? true,
            'max_usage' => $request->max_usage ?? 0,
            'used_count' => 0
        ];

        $voucher = Voucher::create($data);

        $formattedVoucher = [
            'id' => $voucher->id,
            'code' => $voucher->code,
            'type' => $voucher->discount_type,
            'value' => $voucher->discount_value,
            'min_order' => $voucher->minimum_purchase,
            'max_discount' => $voucher->max_discount,
            'start_date' => $voucher->start_date,
            'end_date' => $voucher->end_date,
            'is_active' => $voucher->is_active,
            'created_at' => $voucher->created_at,
            'updated_at' => $voucher->updated_at
        ];

        return response()->json([
            'success' => true,
            'message' => 'Voucher berhasil dibuat',
            'data' => [
                'voucher' => $formattedVoucher
            ]
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $voucher = Voucher::find($id);
        if (!$voucher) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'code' => 'required|string|unique:vouchers,code,' . $id,
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'min_order' => 'required|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = [
            'code' => $request->code,
            'discount_type' => $request->type,
            'discount_value' => $request->value,
            'minimum_purchase' => $request->min_order,
            'max_discount' => $request->max_discount,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->is_active
        ];

        $voucher->update($data);

        $formattedVoucher = [
            'id' => $voucher->id,
            'code' => $voucher->code,
            'type' => $voucher->discount_type,
            'value' => $voucher->discount_value,
            'min_order' => $voucher->minimum_purchase,
            'max_discount' => $voucher->max_discount,
            'start_date' => $voucher->start_date,
            'end_date' => $voucher->end_date,
            'is_active' => $voucher->is_active,
            'created_at' => $voucher->created_at,
            'updated_at' => $voucher->updated_at
        ];

        return response()->json([
            'success' => true,
            'message' => 'Voucher berhasil diupdate',
            'data' => [
                'voucher' => $formattedVoucher
            ]
        ]);
    }

    public function destroy($id)
    {
        $voucher = Voucher::find($id);
        if (!$voucher) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher tidak ditemukan'
            ], 404);
        }

        $voucher->delete();

        return response()->json([
            'success' => true,
            'message' => 'Voucher berhasil dihapus'
        ]);
    }

    public function toggleActive($id)
    {
        $voucher = Voucher::find($id);
        if (!$voucher) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher tidak ditemukan'
            ], 404);
        }

        $voucher->is_active = !$voucher->is_active;
        $voucher->save();

        $formattedVoucher = [
            'id' => $voucher->id,
            'code' => $voucher->code,
            'type' => $voucher->discount_type,
            'value' => $voucher->discount_value,
            'min_order' => $voucher->minimum_purchase,
            'max_discount' => $voucher->max_discount,
            'start_date' => $voucher->start_date,
            'end_date' => $voucher->end_date,
            'is_active' => $voucher->is_active,
            'created_at' => $voucher->created_at,
            'updated_at' => $voucher->updated_at
        ];

        return response()->json([
            'success' => true,
            'message' => 'Status voucher berhasil diubah',
            'data' => [
                'voucher' => $formattedVoucher
            ]
        ]);
    }

    public function validate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string',
            'total_amount' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $voucher = Voucher::where('code', $request->code)
            ->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        if (!$voucher) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher tidak valid atau sudah kadaluarsa'
            ], 404);
        }

        if ($request->total_amount < $voucher->minimum_purchase) {
            return response()->json([
                'success' => false,
                'message' => 'Total pesanan belum memenuhi minimum pembelian'
            ], 400);
        }

        $discountAmount = $voucher->discount_type === 'fixed' 
            ? $voucher->discount_value 
            : min(
                ($request->total_amount * $voucher->discount_value / 100),
                $voucher->max_discount ?? PHP_FLOAT_MAX
            );

        $formattedVoucher = [
            'id' => $voucher->id,
            'code' => $voucher->code,
            'type' => $voucher->discount_type,
            'value' => $voucher->discount_value,
            'min_order' => $voucher->minimum_purchase,
            'max_discount' => $voucher->max_discount,
            'start_date' => $voucher->start_date,
            'end_date' => $voucher->end_date,
            'is_active' => $voucher->is_active,
            'created_at' => $voucher->created_at,
            'updated_at' => $voucher->updated_at
        ];
        
        return response()->json([
            'success' => true,
            'message' => 'Voucher valid',
            'data' => [
                'voucher' => $formattedVoucher,
                'discount_amount' => $discountAmount
            ]
        ]);
    }
}