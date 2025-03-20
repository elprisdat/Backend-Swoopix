# API Documentation

Base URL: `http://localhost:8080/api/v1`

## Authentication

### Register
- **URL**: `/register`
- **Method**: `POST`
- **Request Body**:
  ```json
  {
    "name": "string",
    "email": "string|optional",
    "phone": "string",
    "password": "string"
  }
  ```
- **Response**: 
  ```json
  {
    "success": true,
    "message": "Berhasil mendaftar, silahkan verifikasi OTP",
    "data": {
      "user": {
        "id": "uuid",
        "name": "string",
        "email": "string|null",
        "phone": "string",
        "is_verified": false,
        "created_at": "timestamp",
        "updated_at": "timestamp"
      },
      "otp": "string" // only in development
    }
  }
  ```

### Verify OTP
- **URL**: `/verify-otp`
- **Method**: `POST`
- **Request Body**:
  ```json
  {
    "phone": "string",
    "otp": "string"
  }
  ```
- **Response**:
  ```json
  {
    "success": true,
    "message": "Verifikasi berhasil"
  }
  ```

### Login
- **URL**: `/login`
- **Method**: `POST`
- **Request Body**:
  ```json
  {
    "phone": "string",
    "password": "string"
  }
  ```
- **Response**:
  ```json
  {
    "success": true,
    "message": "Login berhasil",
    "data": {
      "user": {
        "id": "uuid",
        "name": "string",
        "email": "string|null",
        "phone": "string",
        "is_verified": true,
        "created_at": "timestamp",
        "updated_at": "timestamp"
      },
      "token": "string"
    }
  }
  ```

### Get Profile
- **URL**: `/profile`
- **Method**: `GET`
- **Response**:
  ```json
  {
    "success": true,
    "data": {
      "user": {
        "id": "uuid",
        "name": "string",
        "email": "string|null",
        "phone": "string",
        "is_verified": true,
        "created_at": "timestamp",
        "updated_at": "timestamp"
      }
    }
  }
  ```

### Logout
- **URL**: `/logout`
- **Method**: `POST`
- **Response**:
  ```json
  {
    "success": true,
    "message": "Berhasil logout"
  }
  ```

## Categories

### Get All Categories
- **URL**: `/categories`
- **Method**: `GET`
- **Response**:
  ```json
  {
    "success": true,
    "data": {
      "categories": [
        {
          "id": "uuid",
          "name": "string",
          "description": "string|null",
          "image": "string|null",
          "is_active": "boolean",
          "created_at": "timestamp",
          "updated_at": "timestamp"
        }
      ]
    }
  }
  ```

### Get Active Categories
- **URL**: `/categories/active`
- **Method**: `GET`
- **Response**:
  ```json
  {
    "success": true,
    "data": {
      "categories": [
        {
          "id": "uuid",
          "name": "string",
          "description": "string|null",
          "image": "string|null",
          "is_active": true,
          "created_at": "timestamp",
          "updated_at": "timestamp"
        }
      ]
    }
  }
  ```

### Get Category with Menus
- **URL**: `/categories/{id}`
- **Method**: `GET`
- **Response**:
  ```json
  {
    "success": true,
    "data": {
      "category": {
        "id": "uuid",
        "name": "string",
        "description": "string|null",
        "image": "string|null",
        "is_active": "boolean",
        "created_at": "timestamp",
        "updated_at": "timestamp",
        "menus": [
          {
            "id": "uuid",
            "name": "string",
            "description": "string|null",
            "price": "decimal",
            "image": "string|null",
            "is_available": "boolean",
            "category_id": "uuid",
            "store_id": "uuid",
            "created_at": "timestamp",
            "updated_at": "timestamp"
          }
        ]
      }
    }
  }
  ```

### Create Category
- **URL**: `/categories`
- **Method**: `POST`
- **Request Body** (multipart/form-data):
  ```json
  {
    "name": "string",
    "description": "string|optional",
    "image": "file|optional",
    "is_active": "boolean|optional"
  }
  ```
- **Response**:
  ```json
  {
    "success": true,
    "message": "Kategori berhasil dibuat",
    "data": {
      "category": {
        "id": "uuid",
        "name": "string",
        "description": "string|null",
        "image": "string|null",
        "is_active": "boolean",
        "created_at": "timestamp",
        "updated_at": "timestamp"
      }
    }
  }
  ```

### Update Category
- **URL**: `/categories/{id}`
- **Method**: `POST`
- **Request Body** (multipart/form-data):
  ```json
  {
    "name": "string",
    "description": "string|optional",
    "image": "file|optional",
    "is_active": "boolean|optional"
  }
  ```
- **Response**:
  ```json
  {
    "success": true,
    "message": "Kategori berhasil diupdate",
    "data": {
      "category": {
        "id": "uuid",
        "name": "string",
        "description": "string|null",
        "image": "string|null",
        "is_active": "boolean",
        "created_at": "timestamp",
        "updated_at": "timestamp"
      }
    }
  }
  ```

### Delete Category
- **URL**: `/categories/{id}`
- **Method**: `DELETE`
- **Response**:
  ```json
  {
    "success": true,
    "message": "Kategori berhasil dihapus"
  }
  ```

### Toggle Category Active Status
- **URL**: `/categories/{id}/toggle-active`
- **Method**: `POST`
- **Response**:
  ```json
  {
    "success": true,
    "message": "Status kategori berhasil diubah",
    "data": {
      "category": {
        "id": "uuid",
        "name": "string",
        "description": "string|null",
        "image": "string|null",
        "is_active": "boolean",
        "created_at": "timestamp",
        "updated_at": "timestamp"
      }
    }
  }
  ```

## Menus

### Get All Menus
- **URL**: `/menus`
- **Method**: `GET`
- **Response**:
  ```json
  {
    "success": true,
    "data": {
      "menus": [
        {
          "id": "integer",
          "name": "string",
          "description": "string|null",
          "price": "decimal",
          "category_id": "integer",
          "image": "string|null",
          "is_available": "boolean",
          "created_at": "timestamp",
          "updated_at": "timestamp"
        }
      ]
    }
  }
  ```

### Get Available Menus
- **URL**: `/menus/available`
- **Method**: `GET`
- **Response**:
  ```json
  {
    "success": true,
    "data": {
      "menus": [
        {
          "id": "integer",
          "name": "string",
          "description": "string|null",
          "price": "decimal",
          "category_id": "integer",
          "image": "string|null",
          "is_available": true,
          "created_at": "timestamp",
          "updated_at": "timestamp"
        }
      ]
    }
  }
  ```

### Search Menus
- **URL**: `/menus/search`
- **Method**: `GET`
- **Query Parameters**:
  ```json
  {
    "query": "string"
  }
  ```
- **Response**:
  ```json
  {
    "success": true,
    "data": {
      "menus": [
        {
          "id": "integer",
          "name": "string",
          "description": "string|null",
          "price": "decimal",
          "category_id": "integer",
          "image": "string|null",
          "is_available": "boolean",
          "created_at": "timestamp",
          "updated_at": "timestamp"
        }
      ]
    }
  }
  ```

### Get Menus by Price Range
- **URL**: `/menus/price-range`
- **Method**: `GET`
- **Query Parameters**:
  ```json
  {
    "min": "decimal|optional",
    "max": "decimal|optional"
  }
  ```
- **Response**:
  ```json
  {
    "success": true,
    "data": {
      "menus": [
        {
          "id": "integer",
          "name": "string",
          "description": "string|null",
          "price": "decimal",
          "category_id": "integer",
          "image": "string|null",
          "is_available": "boolean",
          "created_at": "timestamp",
          "updated_at": "timestamp"
        }
      ]
    }
  }
  ```

### Get Menus by Category
- **URL**: `/menus/category/{categoryId}`
- **Method**: `GET`
- **Response**:
  ```json
  {
    "success": true,
    "data": {
      "menus": [
        {
          "id": "integer",
          "name": "string",
          "description": "string|null",
          "price": "decimal",
          "category_id": "integer",
          "image": "string|null",
          "is_available": "boolean",
          "created_at": "timestamp",
          "updated_at": "timestamp"
        }
      ]
    }
  }
  ```

### Get Available Menus by Category
- **URL**: `/menus/category/{categoryId}/available`
- **Method**: `GET`
- **Response**:
  ```json
  {
    "success": true,
    "data": {
      "menus": [
        {
          "id": "integer",
          "name": "string",
          "description": "string|null",
          "price": "decimal",
          "category_id": "integer",
          "image": "string|null",
          "is_available": true,
          "created_at": "timestamp",
          "updated_at": "timestamp"
        }
      ]
    }
  }
  ```

### Get Menu Detail
- **URL**: `/menus/{id}`
- **Method**: `GET`
- **Response**:
  ```json
  {
    "success": true,
    "data": {
      "menu": {
        "id": "integer",
        "name": "string",
        "description": "string|null",
        "price": "decimal",
        "category_id": "integer",
        "image": "string|null",
        "is_available": "boolean",
        "created_at": "timestamp",
        "updated_at": "timestamp",
        "category": {
          "id": "integer",
          "name": "string",
          "description": "string|null",
          "image": "string|null",
          "is_active": "boolean",
          "created_at": "timestamp",
          "updated_at": "timestamp"
        }
      }
    }
  }
  ```

### Create Menu
- **URL**: `/menus`
- **Method**: `POST`
- **Request Body** (multipart/form-data):
  ```json
  {
    "name": "string",
    "description": "string|optional",
    "price": "decimal",
    "category_id": "integer",
    "image": "file|optional",
    "is_available": "boolean|optional"
  }
  ```
- **Response**:
  ```json
  {
    "success": true,
    "message": "Menu berhasil dibuat",
    "data": {
      "menu": {
        "id": "integer",
        "name": "string",
        "description": "string|null",
        "price": "decimal",
        "category_id": "integer",
        "image": "string|null",
        "is_available": "boolean",
        "created_at": "timestamp",
        "updated_at": "timestamp"
      }
    }
  }
  ```

### Update Menu
- **URL**: `/menus/{id}`
- **Method**: `POST`
- **Request Body** (multipart/form-data):
  ```json
  {
    "name": "string",
    "description": "string|optional",
    "price": "decimal",
    "category_id": "integer",
    "image": "file|optional",
    "is_available": "boolean|optional"
  }
  ```
- **Response**:
  ```json
  {
    "success": true,
    "message": "Menu berhasil diupdate",
    "data": {
      "menu": {
        "id": "integer",
        "name": "string",
        "description": "string|null",
        "price": "decimal",
        "category_id": "integer",
        "image": "string|null",
        "is_available": "boolean",
        "created_at": "timestamp",
        "updated_at": "timestamp"
      }
    }
  }
  ```

### Delete Menu
- **URL**: `/menus/{id}`
- **Method**: `DELETE`
- **Response**:
  ```json
  {
    "success": true,
    "message": "Menu berhasil dihapus"
  }
  ```

### Toggle Menu Available Status
- **URL**: `/menus/{id}/toggle-available`
- **Method**: `POST`
- **Response**:
  ```json
  {
    "success": true,
    "message": "Status menu berhasil diubah",
    "data": {
      "menu": {
        "id": "integer",
        "name": "string",
        "description": "string|null",
        "price": "decimal",
        "category_id": "integer",
        "image": "string|null",
        "is_available": "boolean",
        "created_at": "timestamp",
        "updated_at": "timestamp"
      }
    }
  }
  ```

### Get Menu Recommendations by Weather
- **URL**: `/menus/weather-recommendations`
- **Method**: `GET`
- **Query Parameters**:
  ```json
  {
    "lat": "float|required",
    "lon": "float|required"
  }
  ```
- **Response**:
  ```json
  {
    "success": true,
    "data": {
      "weather": {
        "temp": "float",
        "feels_like": "float",
        "humidity": "integer",
        "weather": {
          "id": "integer",
          "main": "string",
          "description": "string"
        }
      },
      "conditions": [
        "string"
      ],
      "menus": [
        {
          "id": "integer",
          "name": "string",
          "description": "string|null",
          "price": "decimal",
          "category_id": "integer",
          "image": "string|null",
          "is_available": true,
          "created_at": "timestamp",
          "updated_at": "timestamp",
          "category": {
            "id": "integer",
            "name": "string",
            "description": "string|null",
            "image": "string|null",
            "is_active": true,
            "created_at": "timestamp",
            "updated_at": "timestamp"
          }
        }
      ]
    }
  }
  ```

## Stores

### Get All Stores
- **URL**: `/stores`
- **Method**: `GET`
- **Response**:
  ```json
  {
    "success": true,
    "data": {
      "stores": [
        {
          "id": "uuid",
          "name": "string",
          "description": "string|null",
          "address": "string",
          "phone": "string",
          "email": "string|null",
          "logo": "string|null",
          "latitude": "decimal",
          "longitude": "decimal",
          "open_time": "time",
          "close_time": "time",
          "is_open": "boolean",
          "created_at": "timestamp",
          "updated_at": "timestamp"
        }
      ]
    }
  }
  ```

### Get Open Stores
- **URL**: `/stores/open`
- **Method**: `GET`
- **Response**:
  ```json
  {
    "success": true,
    "data": {
      "stores": [
        {
          "id": "uuid",
          "name": "string",
          "address": "string|null",
          "phone": "string|null",
          "email": "string|null",
          "logo": "string|null",
          "description": "string|null",
          "open_time": "time|null",
          "close_time": "time|null",
          "is_open": true,
          "created_at": "timestamp",
          "updated_at": "timestamp"
        }
      ]
    }
  }
  ```

### Search Stores
- **URL**: `/stores/search`
- **Method**: `GET`
- **Query Parameters**:
  ```json
  {
    "query": "string"
  }
  ```
- **Response**:
  ```json
  {
    "success": true,
    "data": {
      "stores": [
        {
          "id": "uuid",
          "name": "string",
          "address": "string|null",
          "phone": "string|null",
          "email": "string|null",
          "logo": "string|null",
          "description": "string|null",
          "open_time": "time|null",
          "close_time": "time|null",
          "is_open": "boolean",
          "created_at": "timestamp",
          "updated_at": "timestamp"
        }
      ]
    }
  }
  ```

### Get Store Detail
- **URL**: `/stores/{id}`
- **Method**: `GET`
- **Response**:
  ```json
  {
    "success": true,
    "data": {
      "store": {
        "id": "uuid",
        "name": "string",
        "address": "string|null",
        "phone": "string|null",
        "email": "string|null",
        "logo": "string|null",
        "description": "string|null",
        "open_time": "time|null",
        "close_time": "time|null",
        "is_open": "boolean",
        "created_at": "timestamp",
        "updated_at": "timestamp",
        "menus": [
          {
            "id": "uuid",
            "name": "string",
            "description": "string|null",
            "price": "decimal",
            "image": "string|null",
            "is_available": "boolean",
            "category_id": "uuid",
            "store_id": "uuid",
            "created_at": "timestamp",
            "updated_at": "timestamp",
            "category": {
              "id": "uuid",
              "name": "string",
              "description": "string|null",
              "image": "string|null",
              "is_active": "boolean"
            }
          }
        ]
      }
    }
  }
  ```

### Create Store
- **URL**: `/stores`
- **Method**: `POST`
- **Request Body** (multipart/form-data):
  ```json
  {
    "name": "string",
    "address": "string|optional",
    "phone": "string|optional",
    "email": "string|optional",
    "logo": "file|optional",
    "description": "string|optional",
    "open_time": "time|optional",
    "close_time": "time|optional",
    "is_open": "boolean|optional"
  }
  ```
- **Response**:
  ```json
  {
    "success": true,
    "message": "Toko berhasil dibuat",
    "data": {
      "store": {
        "id": "uuid",
        "name": "string",
        "address": "string|null",
        "phone": "string|null",
        "email": "string|null",
        "logo": "string|null",
        "description": "string|null",
        "open_time": "time|null",
        "close_time": "time|null",
        "is_open": "boolean",
        "created_at": "timestamp",
        "updated_at": "timestamp"
      }
    }
  }
  ```

### Update Store
- **URL**: `/stores/{id}`
- **Method**: `POST`
- **Request Body** (multipart/form-data):
  ```json
  {
    "name": "string",
    "address": "string|optional",
    "phone": "string|optional",
    "email": "string|optional",
    "logo": "file|optional",
    "description": "string|optional",
    "open_time": "time|optional",
    "close_time": "time|optional",
    "is_open": "boolean|optional"
  }
  ```
- **Response**:
  ```json
  {
    "success": true,
    "message": "Toko berhasil diupdate",
    "data": {
      "store": {
        "id": "uuid",
        "name": "string",
        "address": "string|null",
        "phone": "string|null",
        "email": "string|null",
        "logo": "string|null",
        "description": "string|null",
        "open_time": "time|null",
        "close_time": "time|null",
        "is_open": "boolean",
        "created_at": "timestamp",
        "updated_at": "timestamp"
      }
    }
  }
  ```

### Delete Store
- **URL**: `/stores/{id}`
- **Method**: `DELETE`
- **Response**:
  ```json
  {
    "success": true,
    "message": "Toko berhasil dihapus"
  }
  ```

### Toggle Store Open Status
- **URL**: `/stores/{id}/toggle-open`
- **Method**: `POST`
- **Response**:
  ```json
  {
    "success": true,
    "message": "Status toko berhasil diubah",
    "data": {
      "store": {
        "id": "uuid",
        "name": "string",
        "address": "string|null",
        "phone": "string|null",
        "email": "string|null",
        "logo": "string|null",
        "description": "string|null",
        "open_time": "time|null",
        "close_time": "time|null",
        "is_open": "boolean",
        "created_at": "timestamp",
        "updated_at": "timestamp"
      }
    }
  }
  ```

### Get Nearby Stores
- **URL**: `/stores/nearby`
- **Method**: `GET`
- **Query Parameters**:
  ```json
  {
    "latitude": "float|required|between:-90,90",
    "longitude": "float|required|between:-180,180",
    "radius": "float|optional|min:0|max:50" // dalam kilometer, default 5km
  }
  ```
- **Response**:
  ```json
  {
    "success": true,
    "message": "Daftar toko terdekat berhasil didapatkan",
    "data": {
      "stores": [
        {
          "id": "uuid",
          "name": "string",
          "address": "string",
          "phone": "string",
          "email": "string",
          "description": "string",
          "latitude": "float",
          "longitude": "float",
          "open_time": "time",
          "close_time": "time",
          "is_open": "boolean",
          "distance": "float", // dalam kilometer
          "menus": [
            {
              "id": "uuid",
              "name": "string",
              "description": "string",
              "price": "decimal",
              "category_id": "uuid",
              "is_available": "boolean"
            }
          ]
        }
      ],
      "total": "integer"
    }
  }
  ```

## Orders

### Get Orders
- **URL**: `/orders`
- **Method**: `GET`
- **Query Parameters**:
  ```json
  {
    "store_id": "uuid|optional",
    "status": "string|optional"
  }
  ```
- **Response**:
  ```json
  {
    "success": true,
    "data": {
      "orders": [
        {
          "id": "uuid",
          "user_id": "uuid",
          "store_id": "uuid",
          "voucher_id": "uuid|null",
          "total_price": "decimal",
          "discount_amount": "decimal",
          "final_price": "decimal",
          "status": "string",
          "payment_status": "string",
          "payment_method": "string|null",
          "payment_url": "string|null",
          "payment_token": "string|null",
          "expired_at": "timestamp",
          "paid_at": "timestamp|null",
          "cancelled_at": "timestamp|null",
          "completed_at": "timestamp|null",
          "notes": "string|null",
          "created_at": "timestamp",
          "updated_at": "timestamp",
          "store": {
            "id": "uuid",
            "name": "string",
            "address": "string|null",
            "phone": "string|null",
            "email": "string|null",
            "logo": "string|null",
            "description": "string|null",
            "open_time": "time|null",
            "close_time": "time|null",
            "is_open": "boolean",
            "created_at": "timestamp",
            "updated_at": "timestamp"
          },
          "items": [
            {
              "id": "uuid",
              "order_id": "uuid",
              "menu_id": "uuid",
              "quantity": "integer",
              "price": "decimal",
              "subtotal": "decimal",
              "created_at": "timestamp",
              "updated_at": "timestamp",
              "menu": {
                "id": "uuid",
                "name": "string",
                "description": "string|null",
                "price": "decimal",
                "category_id": "uuid",
                "image": "string|null",
                "is_available": "boolean",
                "created_at": "timestamp",
                "updated_at": "timestamp"
              }
            }
          ],
          "payment": [
            {
              "id": "uuid",
              "order_id": "uuid",
              "amount": "decimal",
              "method": "string",
              "status": "string",
              "token": "string",
              "url": "string|null",
              "expired_at": "timestamp",
              "paid_at": "timestamp|null",
              "created_at": "timestamp",
              "updated_at": "timestamp"
            }
          ]
        }
      ]
    }
  }
  ```

### Get Order Detail
- **URL**: `/orders/{id}`
- **Method**: `GET`
- **Response**:
  ```json
  {
    "success": true,
    "data": {
      "order": {
        "id": "uuid",
        "user_id": "uuid",
        "store_id": "uuid",
        "voucher_id": "uuid|null",
        "total_price": "decimal",
        "discount_amount": "decimal",
        "final_price": "decimal",
        "status": "string",
        "payment_status": "string",
        "payment_method": "string|null",
        "payment_url": "string|null",
        "payment_token": "string|null",
        "expired_at": "timestamp",
        "paid_at": "timestamp|null",
        "cancelled_at": "timestamp|null",
        "completed_at": "timestamp|null",
        "notes": "string|null",
        "created_at": "timestamp",
        "updated_at": "timestamp",
        "store": {
          "id": "uuid",
          "name": "string",
          "address": "string|null",
          "phone": "string|null",
          "email": "string|null",
          "logo": "string|null",
          "description": "string|null",
          "open_time": "time|null",
          "close_time": "time|null",
          "is_open": "boolean",
          "created_at": "timestamp",
          "updated_at": "timestamp"
        },
        "items": [
          {
            "id": "uuid",
            "order_id": "uuid",
            "menu_id": "uuid",
            "quantity": "integer",
            "price": "decimal",
            "subtotal": "decimal",
            "created_at": "timestamp",
            "updated_at": "timestamp",
            "menu": {
              "id": "uuid",
              "name": "string",
              "description": "string|null",
              "price": "decimal",
              "category_id": "uuid",
              "image": "string|null",
              "is_available": "boolean",
              "created_at": "timestamp",
              "updated_at": "timestamp"
            }
          }
        ],
        "payment": [
          {
            "id": "uuid",
            "order_id": "uuid",
            "amount": "decimal",
            "method": "string",
            "status": "string",
            "token": "string",
            "url": "string|null",
            "expired_at": "timestamp",
            "paid_at": "timestamp|null",
            "created_at": "timestamp",
            "updated_at": "timestamp"
          }
        ],
        "voucher": {
          "id": "uuid",
          "id": "string",
          "code": "string",
          "type": "string",
          "value": "decimal",
          "min_order": "decimal",
          "max_discount": "decimal|null",
          "start_date": "timestamp",
          "end_date": "timestamp",
          "is_active": "boolean",
          "created_at": "timestamp",
          "updated_at": "timestamp"
        }
      }
    }
  }
  ```

### Create Order
- **URL**: `/orders`
- **Method**: `POST`
- **Request Body**:
  ```json
  {
    "store_id": "string",
    "items": [
      {
        "menu_id": "string",
        "quantity": "integer"
      }
    ],
    "voucher_id": "string|optional",
    "notes": "string|optional"
  }
  ```
- **Response**:
  ```json
  {
    "success": true,
    "message": "Pesanan berhasil dibuat",
    "data": {
      "order": {
        "id": "string",
        "user_id": "string",
        "store_id": "string",
        "voucher_id": "string|null",
        "total_price": "decimal",
        "discount_amount": "decimal",
        "final_price": "decimal",
        "status": "pending",
        "payment_status": "unpaid",
        "payment_method": null,
        "payment_url": null,
        "payment_token": null,
        "expired_at": "timestamp",
        "paid_at": null,
        "cancelled_at": null,
        "completed_at": null,
        "notes": "string|null",
        "created_at": "timestamp",
        "updated_at": "timestamp",
        "store": {
          "id": "string",
          "name": "string",
          "address": "string|null",
          "phone": "string|null",
          "email": "string|null",
          "logo": "string|null",
          "description": "string|null",
          "open_time": "time|null",
          "close_time": "time|null",
          "is_open": "boolean",
          "created_at": "timestamp",
          "updated_at": "timestamp"
        },
        "items": [
          {
            "id": "integer",
            "order_id": "string",
            "menu_id": "string",
            "quantity": "integer",
            "price": "decimal",
            "subtotal": "decimal",
            "created_at": "timestamp",
            "updated_at": "timestamp",
            "menu": {
              "id": "string",
              "name": "string",
              "description": "string|null",
              "price": "decimal",
              "category_id": "integer",
              "image": "string|null",
              "is_available": "boolean",
              "created_at": "timestamp",
              "updated_at": "timestamp"
            }
          }
        ]
      }
    }
  }
  ```

### Update Order Status
- **URL**: `/orders/{id}/status`
- **Method**: `POST`
- **Request Body**:
  ```json
  {
    "status": "string|in:processing,completed,cancelled"
  }
  ```
- **Response**:
  ```json
  {
    "success": true,
    "message": "Status pesanan berhasil diubah",
    "data": {
      "order": {
        // Order object like in Get Order Detail
      }
    }
  }
  ```

### Update Payment Status
- **URL**: `/orders/{id}/payment-status`
- **Method**: `POST`
- **Request Body**:
  ```json
  {
    "status": "string|in:paid,failed,expired",
    "payment_method": "string|required_if:status,paid",
    "payment_token": "string|required_if:status,paid"
  }
  ```
- **Response**:
  ```json
  {
    "success": true,
    "message": "Status pembayaran berhasil diubah",
    "data": {
      "order": {
        // Order object like in Get Order Detail
      }
    }
  }
  ```

### Cancel Order
- **URL**: `/orders/{id}/cancel`
- **Method**: `POST`
- **Response**:
  ```json
  {
    "success": true,
    "message": "Pesanan berhasil dibatalkan",
    "data": {
      "order": {
        // Order object like in Get Order Detail
      }
    }
  }
  ```

### Add Item to Order
- **URL**: `/orders/{orderId}/items`
- **Method**: `POST`
- **Request Body**:
  ```json
  {
    "menu_id": "string|required",
    "quantity": "integer|required|min:1"
  }
  ```
- **Response**:
  ```json
  {
    "success": true,
    "message": "Item berhasil ditambahkan",
    "data": {
      "item": {
        "id": "integer",
        "order_id": "string",
        "menu_id": "string",
        "quantity": "integer",
        "price": "decimal",
        "subtotal": "decimal",
        "created_at": "timestamp",
        "updated_at": "timestamp",
        "menu": {
          "id": "string",
          "name": "string",
          "description": "string|null",
          "price": "decimal",
          "category_id": "integer",
          "image": "string|null",
          "is_available": "boolean",
          "created_at": "timestamp",
          "updated_at": "timestamp"
        }
      },
      "order": {
        // Order object like in Get Order Detail
      }
    }
  }
  ```

### Update Order Item
- **URL**: `/orders/{orderId}/items/{itemId}`
- **Method**: `POST`
- **Request Body**:
  ```json
  {
    "quantity": "integer|required|min:1"
  }
  ```
- **Response**:
  ```json
  {
    "success": true,
    "message": "Item berhasil diupdate",
    "data": {
      "item": {
        "id": "integer",
        "order_id": "string",
        "menu_id": "string",
        "quantity": "integer",
        "price": "decimal",
        "subtotal": "decimal",
        "created_at": "timestamp",
        "updated_at": "timestamp",
        "menu": {
          "id": "string",
          "name": "string",
          "description": "string|null",
          "price": "decimal",
          "category_id": "integer",
          "image": "string|null",
          "is_available": "boolean",
          "created_at": "timestamp",
          "updated_at": "timestamp"
        }
      },
      "order": {
        // Order object like in Get Order Detail
      }
    }
  }
  ```

### Remove Order Item
- **URL**: `/orders/{orderId}/items/{itemId}`
- **Method**: `DELETE`
- **Response**:
  ```json
  {
    "success": true,
    "message": "Item berhasil dihapus",
    "data": {
      "order": {
        // Order object like in Get Order Detail
      }
    }
  }
  ```

## Payments

### Get Payment Channels
- **URL**: `/payment-channels`
- **Method**: `GET`
- **Response**:
  ```json
  {
    "success": true,
    "data": {
      "payment_channels": [
        {
          "code": "BRIVA",
          "name": "BRI Virtual Account",
          "type": "virtual_account",
          "fee_merchant": 3000,
          "fee_customer": 0,
          "total_fee": 3000,
          "minimum_amount": 10000,
          "maximum_amount": 100000000,
          "icon_url": "https://tripay.co.id/images/payment-channel/briva.png"
        }
      ]
    }
  }
  ```

### Create Payment
- **URL**: `/orders/{order_id}/pay`
- **Method**: `POST`
- **Request Body**:
  ```json
  {
    "payment_method": "BRIVA"
  }
  ```
- **Response**:
  ```json
  {
    "success": true,
    "message": "Payment URL berhasil dibuat",
    "data": {
      "payment": {
        "id": "string",
        "order_id": "string",
        "reference_id": "T123456789",
        "payment_method": "BRIVA",
        "amount": "decimal",
        "payment_url": "https://tripay.co.id/checkout/T123456789",
        "status": "pending",
        "payment_details": {
          "reference": "T123456789",
          "merchant_ref": "INV-123",
          "payment_method": "BRIVA",
          "payment_name": "BRI Virtual Account",
          "customer_name": "John Doe",
          "customer_email": "john@example.com",
          "customer_phone": "081234567890",
          "callback_url": "https://example.com/callback",
          "return_url": "https://example.com/return",
          "amount": 50000,
          "fee": 3000,
          "total_amount": 53000,
          "checkout_url": "https://tripay.co.id/checkout/T123456789",
          "status": "UNPAID",
          "expired_time": 1679904000
        },
        "created_at": "timestamp",
        "updated_at": "timestamp"
      }
    }
  }
  ```

## Vouchers

### Get All Vouchers
- **URL**: `/vouchers`
- **Method**: `GET`
- **Response**:
  ```json
  {
    "success": true,
    "data": {
      "vouchers": [
        {
          "id": "string",
          "code": "string",
          "type": "fixed|percentage",
          "value": "decimal",
          "min_order": "decimal",
          "max_discount": "decimal|null",
          "start_date": "timestamp",
          "end_date": "timestamp",
          "is_active": "boolean",
          "created_at": "timestamp",
          "updated_at": "timestamp"
        }
      ]
    }
  }
  ```

### Get Active Vouchers
- **URL**: `/vouchers/active`
- **Method**: `GET`
- **Response**:
  ```json
  {
    "success": true,
    "data": {
      "vouchers": [
        {
          "id": "string",
          "code": "string",
          "type": "fixed|percentage",
          "value": "decimal",
          "min_order": "decimal",
          "max_discount": "decimal|null",
          "start_date": "timestamp",
          "end_date": "timestamp",
          "is_active": true,
          "created_at": "timestamp",
          "updated_at": "timestamp"
        }
      ]
    }
  }
  ```

### Get Voucher Detail
- **URL**: `/vouchers/{id}`
- **Method**: `GET`
- **Response**:
  ```json
  {
    "success": true,
    "data": {
      "voucher": {
        "id": "string",
        "code": "string",
        "type": "fixed|percentage",
        "value": "decimal",
        "min_order": "decimal",
        "max_discount": "decimal|null",
        "start_date": "timestamp",
        "end_date": "timestamp",
        "is_active": "boolean",
        "created_at": "timestamp",
        "updated_at": "timestamp"
      }
    }
  }
  ```

### Create Voucher
- **URL**: `/vouchers`
- **Method**: `POST`
- **Request Body**:
  ```json
  {
    "code": "string|required|unique",
    "type": "string|required|in:fixed,percentage",
    "value": "decimal|required|min:0",
    "min_order": "decimal|required|min:0",
    "max_discount": "decimal|optional|min:0",
    "start_date": "timestamp|required",
    "end_date": "timestamp|required|after:start_date",
    "is_active": "boolean|optional"
  }
  ```
- **Response**:
  ```json
  {
    "success": true,
    "message": "Voucher berhasil dibuat",
    "data": {
      "voucher": {
        "id": "string",
        "code": "string",
        "type": "fixed|percentage",
        "value": "decimal",
        "min_order": "decimal",
        "max_discount": "decimal|null",
        "start_date": "timestamp",
        "end_date": "timestamp",
        "is_active": "boolean",
        "created_at": "timestamp",
        "updated_at": "timestamp"
      }
    }
  }
  ```

### Update Voucher
- **URL**: `/vouchers/{id}`
- **Method**: `POST`
- **Request Body**:
  ```json
  {
    "code": "string|required|unique:vouchers,code,{id}",
    "type": "string|required|in:fixed,percentage",
    "value": "decimal|required|min:0",
    "min_order": "decimal|required|min:0",
    "max_discount": "decimal|optional|min:0",
    "start_date": "timestamp|required",
    "end_date": "timestamp|required|after:start_date",
    "is_active": "boolean|optional"
  }
  ```
- **Response**:
  ```json
  {
    "success": true,
    "message": "Voucher berhasil diupdate",
    "data": {
      "voucher": {
        "id": "string",
        "code": "string",
        "type": "fixed|percentage",
        "value": "decimal",
        "min_order": "decimal",
        "max_discount": "decimal|null",
        "start_date": "timestamp",
        "end_date": "timestamp",
        "is_active": "boolean",
        "created_at": "timestamp",
        "updated_at": "timestamp"
      }
    }
  }
  ```

### Delete Voucher
- **URL**: `/vouchers/{id}`
- **Method**: `DELETE`
- **Response**:
  ```json
  {
    "success": true,
    "message": "Voucher berhasil dihapus"
  }
  ```

### Toggle Voucher Active Status
- **URL**: `/vouchers/{id}/toggle-active`
- **Method**: `POST`
- **Response**:
  ```json
  {
    "success": true,
    "message": "Status voucher berhasil diubah",
    "data": {
      "voucher": {
        "id": "string",
        "code": "string",
        "type": "fixed|percentage",
        "value": "decimal",
        "min_order": "decimal",
        "max_discount": "decimal|null",
        "start_date": "timestamp",
        "end_date": "timestamp",
        "is_active": "boolean",
        "created_at": "timestamp",
        "updated_at": "timestamp"
      }
    }
  }
  ```

### Validate Voucher
- **URL**: `/vouchers/validate`
- **Method**: `POST`
- **Request Body**:
  ```json
  {
    "code": "string|required",
    "total_amount": "decimal|required|min:0"
  }
  ```
- **Response**:
  ```json
  {
    "success": true,
    "message": "Voucher valid",
    "data": {
      "voucher": {
        "id": "string",
        "code": "string",
        "type": "fixed|percentage",
        "value": "decimal",
        "min_order": "decimal",
        "max_discount": "decimal|null",
        "start_date": "timestamp",
        "end_date": "timestamp",
        "is_active": true,
        "created_at": "timestamp",
        "updated_at": "timestamp"
      },
      "discount_amount": "decimal"
    }
  }
  ```
