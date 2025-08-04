# admin-category

This package allows you to perform CRUD operations for managing categories in the admin panel.

## Features

- Create new categories
- View a list of existing categories
- Update category details
- Delete categories

## Usage

1. **Create**: Add a new category with name and description.
2. **Read**: View all categories in a paginated list.
3. **Update**: Edit category information.
4. **Delete**: Remove categories that are no longer needed.

## Example Endpoints

| Method | Endpoint           | Description           |
|--------|-------------------|-----------------------|
| GET    | `/categories`     | List all categories   |
| POST   | `/categories`     | Create a new category |
| GET    | `/categories/{id}`| Get category details  |
| PUT    | `/categories/{id}`| Update a category     |
| DELETE | `/categories/{id}`| Delete a category     |

## Requirements

- PHP 8.2+
- Laravel Framework

## Update `composer.json` file

Add the following to your `composer.json` to use the package from a local path:

```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/pavanraj92/admin-categories.git"
    }
]
```

## Installation

```bash
composer require admin/category:@dev
```

## Usage

1. Publish the configuration and migration files:
    ```bash    
    php artisan categories:publish --force

    composer dump-autoload

    php artisan migrate
    ```
2. Access the Categories manager from your admin dashboard.

## Example

```php
use Admin\Category\Models\Category;

// Creating a new category
$category = new Category();
$category->name = 'Electronics';
$category->description = 'All electronic items';
$category->save();
```

## Customization

You can customize views, routes, and permissions by editing the package's configuration file.


## License

This package is open-sourced software licensed under the Dotsquares.write code in the readme.md file regarding to the admin/categories manager