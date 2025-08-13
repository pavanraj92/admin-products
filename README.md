# admin-product

This package allows you to perform CRUD operations for managing products in the admin panel.

## Features

- Add, edit, and delete products with details like name, price, brand, category, images, etc.
- Upload multiple images per product
- Assign categories, brands, and tags to products
- Manage product inventory, pricing, and status
- View and manage product orders, transactions, returns, and refunds
- Generate sales and transaction reports
- SEO metadata support (optional)
- Searchable, paginated product listing
- Soft-delete support for products

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

1. **Create**: Add a new product with all necessary details, assign categories, brands, tags, and upload images.
2. **Read**: View products, orders, transactions, returns, and refunds in searchable, paginated tables.
3. **Update**: Modify product data, images, inventory, pricing, and attributes.
4. **Delete**: Soft-delete a product from the system.
5. **Manage Orders**: View, update, and manage product orders.
6. **Handle Returns/Refunds**: View and update return/refund requests.
7. **Transactions**: Track and manage product-related transactions.
8. **Reporting**: Generate and view sales and transaction reports.

## Admin Panel Routes

| Method | Endpoint                                 | Description                              |
| ------ | ---------------------------------------- | ---------------------------------------- |
| GET    | /products                                | List all products                        |
| POST   | /products                                | Create a new product                     |
| GET    | /products/{product}                      | Get product details                      |
| PUT    | /products/{product}                      | Update a product                         |
| DELETE | /products/{product}                      | Delete a product                         |
| GET    | /categories/get-children                 | Get child categories (AJAX)              |
| GET    | /categories/subcategories/{id}           | Get subcategories for category           |
| GET    | /categories/{category}/nested-subcategories | Get nested subcategories for category    |
| GET    | /return_refunds                          | List all return/refund requests          |
| GET    | /return_refunds/{return_refund}          | Show return/refund details               |
| POST   | /return_refunds/updateStatus             | Update return/refund status              |
| GET    | /orders                                  | List all orders                          |
| POST   | /orders                                  | Create a new order                       |
| GET    | /orders/{order}                          | Get order details                        |
| PUT    | /orders/{order}                          | Update an order                          |
| DELETE | /orders/{order}                          | Delete an order                          |
| POST   | /orders/updateStatus                     | Update order status                      |
| GET    | /transactions                            | List all transactions                    |
| POST   | /transactions                            | Create a new transaction                 |
| GET    | /transactions/{transaction}              | Get transaction details                  |
| PUT    | /transactions/{transaction}              | Update a transaction                     |
| DELETE | /transactions/{transaction}              | Delete a transaction                     |
| GET    | /reports                                 | View sales and transaction reports       |

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
