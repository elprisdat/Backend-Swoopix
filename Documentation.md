# Dokumentasi API Sistem Pemesanan Kafe

## Daftar Isi
1. [Manajemen Pengguna](#manajemen-pengguna)
2. [Manajemen Menu](#manajemen-menu)
3. [Manajemen Kategori](#manajemen-kategori)
4. [Manajemen Voucher](#manajemen-voucher)
5. [Sistem Pemesanan](#sistem-pemesanan)
6. [Lokasi Toko](#lokasi-toko)
7. [Rekomendasi Menu](#rekomendasi-menu)
8. [Sistem Pembayaran](#sistem-pembayaran)

## Techstack
- Backend: Laravel
- Database: MySql
- Payment Gateway: Tripay
- Weather API: OpenWeather
- WhatsApp API: Fonnte
- Image Storage: Local Storage

## Manajemen Pengguna

### Register User (Step 1 - Request OTP)
- Endpoint: `POST /api/register`
- Body:
```json
{
    "email": "string",
    "password": "string",
    "name": "string",
    "phone": "string"
}
```
- Response (200):
```json
{
    "status": "success",
    "message": "OTP telah dikirim ke WhatsApp Anda"
}
```

### Register User (Step 2 - Verify OTP)
- Endpoint: `POST /api/verify-otp`
- Body:
```json
{
    "phone": "string",
    "otp": "string",
    "email": "string",
    "password": "string",
    "name": "string"
}
```
- Response (200):
```json
{
    "status": "success",
    "data": {
        "user": {
            "id": "uuid",
            "email": "string",
            "name": "string",
            "phone": "string",
            "points": 0
        }
    }
}
```

### Login
- Endpoint: `POST /api/login`
- Body:
```json
{
    "email": "string",
    "password": "string"
}
```
- Response (200):
```json
{
    "status": "success",
    "data": {
        "user": {
            "id": "uuid",
            "email": "string",
            "name": "string",
            "points": "number",
            "role": "string"
        }
    }
}
```

### Get User Profile
- Endpoint: `GET /api/users/profile`
- Response (200):
```json
{
    "status": "success",
    "data": {
        "id": "uuid",
        "email": "string",
        "name": "string",
        "phone": "string",
        "points": "number",
        "orderHistory": []
    }
}
```

## Manajemen Menu

### Get All Menu
- Endpoint: `GET /api/menu`
- Query Parameters:
  - search: string (optional) - Cari berdasarkan nama atau deskripsi
  - category_id: number (optional) - Filter berdasarkan kategori
  - min_price: number (optional) - Filter harga minimum
  - max_price: number (optional) - Filter harga maksimum
  - available: boolean (optional) - Filter berdasarkan ketersediaan
  - sort_by: string (optional) - Field untuk pengurutan (default: created_at)
  - sort_direction: string (optional) - Arah pengurutan (asc/desc, default: desc)
  - per_page: number (optional) - Jumlah item per halaman (default: 10)
- Response (200):
```json
{
    "status": "success",
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": "number",
                "name": "string",
                "description": "string",
                "price": "number",
                "category_id": "number",
                "image": "string",
                "is_available": "boolean",
                "created_at": "datetime",
                "updated_at": "datetime"
            }
        ],
        "first_page_url": "string",
        "from": "number",
        "last_page": "number",
        "last_page_url": "string",
        "next_page_url": "string",
        "path": "string",
        "per_page": "number",
        "prev_page_url": "string",
        "to": "number",
        "total": "number"
    }
}
```

### Get Menu Detail
- Endpoint: `GET /api/menu/{id}`
- Response (200):
```json
{
    "status": "success",
    "data": {
        "id": "number",
        "name": "string",
        "description": "string",
        "price": "number",
        "category_id": "number",
        "image": "string",
        "is_available": "boolean",
        "created_at": "datetime",
        "updated_at": "datetime",
        "category": {
            "id": "number",
            "name": "string",
            "description": "string"
        }
    }
}
```

### Create Menu
- Endpoint: `POST /api/menu`
- Body (multipart/form-data):
```
name: string
description: string
price: number
category_id: number
image: file (optional)
is_available: boolean (optional)
```
- Response (201):
```json
{
    "status": "success",
    "data": {
        "id": "number",
        "name": "string",
        "description": "string",
        "price": "number",
        "category_id": "number",
        "image": "string",
        "is_available": "boolean",
        "created_at": "datetime",
        "updated_at": "datetime"
    }
}
```

### Update Menu
- Endpoint: `PUT /api/menu/{id}`
- Body (multipart/form-data):
```
name: string (optional)
description: string (optional)
price: number (optional)
category_id: number (optional)
image: file (optional)
is_available: boolean (optional)
```
- Response (200):
```json
{
    "status": "success",
    "data": {
        "id": "number",
        "name": "string",
        "description": "string",
        "price": "number",
        "category_id": "number",
        "image": "string",
        "is_available": "boolean",
        "updated_at": "datetime"
    }
}
```

## Sistem Pemesanan

### Create Order
- Endpoint: `POST /api/orders`
- Body:
```json
{
    "items": [
        {
            "menuId": "uuid",
            "quantity": "number"
        }
    ],
    "voucherId": "uuid (optional)",
    "storeId": "uuid",
    "notes": "string"
}
```

### Get Order History
- Endpoint: `GET /api/orders`
- Query Parameters:
  - page: number
  - limit: number
  - status: "pending|processing|completed|cancelled"

## Lokasi Toko

### Get Nearby Stores
- Endpoint: `GET /api/stores/nearby`
- Query Parameters:
```
latitude: number
longitude: number
radius: number (in kilometers)
```
- Response (200):
```json
{
    "status": "success",
    "data": [
        {
            "id": "uuid",
            "name": "string",
            "address": "string",
            "latitude": "number",
            "longitude": "number",
            "distance": "number",
            "isOpen": "boolean",
            "operationalHours": {
                "open": "string",
                "close": "string"
            }
        }
    ]
}
```

## Rekomendasi Menu

### Get Weather-based Recommendations
- Endpoint: `GET /api/menu/recommendations`
- Query Parameters:
```
latitude: number
longitude: number
```
- Response (200):
```json
{
    "status": "success",
    "data": {
        "weather": {
            "condition": "string",
            "temperature": "number"
        },
        "recommendations": [
            {
                "id": "uuid",
                "name": "string",
                "description": "string",
                "price": "number",
                "image": "string",
                "category": "string"
            }
        ]
    }
}
```

## Sistem Pembayaran

### Create Payment
- Endpoint: `POST /api/payments`
- Body:
```json
{
    "orderId": "uuid",
    "paymentMethod": "string"
}
```
- Response (200):
```json
{
    "status": "success",
    "data": {
        "paymentUrl": "string",
        "referenceId": "string",
        "amount": "number",
        "status": "pending"
    }
}
```

### Check Payment Status
- Endpoint: `GET /api/payments/{referenceId}`

## Response Codes

- 200: Success
- 201: Created
- 400: Bad Request
- 404: Not Found
- 500: Internal Server Error

## Format Response

Semua response API akan mengikuti format berikut:

### Success Response
```json
{
    "status": "success",
    "data": {
        // response data
    }
}
```

### Error Response
```json
{
    "status": "error",
    "message": "string",
    "errors": [] // optional detailed errors
}
``` 