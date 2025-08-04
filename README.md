# admin-product

This package allows you to perform CRUD operations for managing products in the admin panel.

## Features

- Add new products with details like name, price, brand, category, images, etc.
- View a paginated list of products
- Edit product details
- Delete products
- Upload multiple images per product
- Assign categories, brands, tags to products
- Manage product inventory and status
- SEO metadata support (optional)

## Requirements

- PHP >=8.2
- Laravel Framework >= 12.x

## Installation

### 1. Add Git Repository to `composer.json`

```json
"repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/pavanraj92/admin-products.git"
        }
]
```

### 2. Require the package via Composer
    ```bash
    composer require admin/products:@dev
    ```

### 3. Publish assets
    ```bash
    php artisan products:publish --force
    ```
---


## Usage

1. **Create**: Add a new product with necessary details like name, price, SKU, images, etc.
2. **Read**: View all products in a searchable, paginated table.
3. **Update**: Modify product data, images, and attributes.
4. **Delete**: Soft-delete a product from the system.

## Admin Panel Routes

| Method | Endpoint         | Description          |
| ------ | ---------------- | -------------------- |
| GET    | `/products`      | List all products    |
| POST   | `/products`      | Create a new product |
| GET    | `/products/{id}` | Get product details  |
| PUT    | `/products/{id}` | Update a product     |
| DELETE | `/products/{id}` | Delete a product     |
---

## Protecting Admin Routes

Protect your routes using the provided middleware:

```php
Route::middleware(['web','admin.auth'])->group(function () {
    // products routes here
});
```

## License

This package is open-sourced software licensed under the MIT license.
