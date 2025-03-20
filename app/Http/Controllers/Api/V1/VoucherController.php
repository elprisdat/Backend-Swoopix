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
        $vouchers = Voucher::all();
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
            ->get();

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

        return response()->json([
            'success' => true,
            'data' => [
                'voucher' => $voucher
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

        $voucher = Voucher::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Voucher berhasil dibuat',
            'data' => [
                'voucher' => $voucher
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

        $voucher->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Voucher berhasil diupdate',
            'data' => [
                'voucher' => $voucher
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

        return response()->json([
            'success' => true,
            'message' => 'Status voucher berhasil diubah',
            'data' => [
                'voucher' => $voucher
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

        if ($request->total_amount < $voucher->min_order) {
            return response()->json([
                'success' => false,
                'message' => 'Total pesanan belum memenuhi minimum pembelian'
            ], 400);
        }

        $discountAmount = $voucher->type === 'fixed' 
            ? $voucher->value 
            : min(
                ($request->total_amount * $voucher->value / 100),
                $voucher->max_discount ?? PHP_FLOAT_MAX
            );

        return response()->json([
            'success' => true,
            'message' => 'Voucher valid',
            'data' => [
                'voucher' => $voucher,
                'discount_amount' => $discountAmount
            ]
        ]);
    }
}