<?php

namespace admin\products;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProductServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load routes, views, migrations from the package  
        $this->loadViewsFrom([
            base_path('Modules/Products/resources/views'), // Published module views first
            resource_path('views/admin/product'), // Published views second
            __DIR__ . '/../resources/views'      // Package views as fallback
        ], 'product');

        $this->mergeConfigFrom(__DIR__ . '/../config/product.php', 'product.constants');

        // Also register module views with a specific namespace for explicit usage
        if (is_dir(base_path('Modules/Products/resources/views'))) {
            $this->loadViewsFrom(base_path('Modules/Products/resources/views'), 'products-module');
        }
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        // Also load migrations from published module if they exist
        if (is_dir(base_path('Modules/Products/database/migrations'))) {
            $this->loadMigrationsFrom(base_path('Modules/Products/database/migrations'));
        }
        $this->mergeConfigFrom(__DIR__ . '/../config/product.php', 'product.config');
        // Also merge config from published module if it exists
        if (file_exists(base_path('Modules/Products/config/products.php'))) {
            $this->mergeConfigFrom(base_path('Modules/Products/config/products.php'), 'product.config');
        }

        // Only publish automatically during package installation, not on every request
        // Use 'php artisan products:publish' command for manual publishing
        // $this->publishWithNamespaceTransformation();

        // Standard publishing for non-PHP files
        $this->publishes([
            __DIR__ . '/../config/' => base_path('Modules/Products/config/'),
            __DIR__ . '/../database/migrations' => base_path('Modules/Products/database/migrations'),
            __DIR__ . '/../resources/views' => base_path('Modules/Products/resources/views/'),
        ], 'product');

        $this->registerAdminRoutes();
    }

    protected function registerAdminRoutes()
    {
        if (!Schema::hasTable('admins')) {
            return; // Avoid errors before migration
        }

        $admin = DB::table('admins')
            ->orderBy('created_at', 'asc')
            ->first();

        $slug = $admin->website_slug ?? 'admin';

        Route::middleware('web')
            ->prefix("{$slug}/admin") // dynamic prefix
            ->group(function () {
                // Load routes from published module first, then fallback to package
                if (file_exists(base_path('Modules/Products/routes/web.php'))) {
                    $this->loadRoutesFrom(base_path('Modules/Products/routes/web.php'));
                } else {
                    $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
                }
            });
    }

    public function register()
    {
        // Register the publish command
        if ($this->app->runningInConsole()) {
            $this->commands([
                \admin\products\Console\Commands\PublishProductsModuleCommand::class,
                \admin\products\Console\Commands\CheckModuleStatusCommand::class,
                \admin\products\Console\Commands\DebugProductsCommand::class,
            ]);
        }
    }

    /**
     * Publish files with namespace transformation
     */
    protected function publishWithNamespaceTransformation()
    {
        // Define the files that need namespace transformation
        $filesWithNamespaces = [
            // Controllers
            __DIR__ . '/../src/Controllers/ProductManagerController.php' => base_path('Modules/Products/app/Http/Controllers/Admin/ProductManagerController.php'),

            // Models
            __DIR__ . '/../src/Models/Product.php' => base_path('Modules/Products/app/Models/Product.php'),

            // Requests
            __DIR__ . '/../src/Requests/ProductCreateRequest.php' => base_path('Modules/Products/app/Http/Requests/ProductCreateRequest.php'),
            __DIR__ . '/../src/Requests/ProductUpdateRequest.php' => base_path('Modules/Products/app/Http/Requests/ProductUpdateRequest.php'),

            // Routes
            __DIR__ . '/routes/web.php' => base_path('Modules/Products/routes/web.php'),
        ];

        foreach ($filesWithNamespaces as $source => $destination) {
            if (File::exists($source)) {
                // Create destination directory if it doesn't exist
                File::ensureDirectoryExists(dirname($destination));

                // Read the source file
                $content = File::get($source);

                // Transform namespaces based on file type
                $content = $this->transformNamespaces($content, $source);

                // Write the transformed content to destination
                File::put($destination, $content);
            }
        }
    }

    /**
     * Transform namespaces in PHP files
     */
    protected function transformNamespaces($content, $sourceFile)
    {
        // Define namespace mappings
        $namespaceTransforms = [
            // Main namespace transformations
            'namespace admin\\products\\Controllers;' => 'namespace Modules\\Products\\app\\Http\\Controllers\\Admin;',
            'namespace admin\\products\\Models;' => 'namespace Modules\\Products\\app\\Models;',
            'namespace admin\\products\\Requests;' => 'namespace Modules\\Products\\app\\Http\\Requests;',

            // Use statements transformations
            'use admin\\products\\Controllers\\' => 'use Modules\\Products\\app\\Http\\Controllers\\Admin\\',
            'use admin\\products\\Models\\' => 'use Modules\\Products\\app\\Models\\',
            'use admin\\products\\Requests\\' => 'use Modules\\Products\\app\\Http\\Requests\\',

            // Class references in routes
            'admin\\products\\Controllers\\ProductManagerController' => 'Modules\\Products\\app\\Http\\Controllers\\Admin\\ProductManagerController',
        ];

        // Apply transformations
        foreach ($namespaceTransforms as $search => $replace) {
            $content = str_replace($search, $replace, $content);
        }

        // Handle specific file types
        if (str_contains($sourceFile, 'Controllers')) {
            $content = $this->transformControllerNamespaces($content);
        } elseif (str_contains($sourceFile, 'Models')) {
            $content = $this->transformModelNamespaces($content);
        } elseif (str_contains($sourceFile, 'Requests')) {
            $content = $this->transformRequestNamespaces($content);
        } elseif (str_contains($sourceFile, 'routes')) {
            $content = $this->transformRouteNamespaces($content);
        }

        return $content;
    }

    /**
     * Transform controller-specific namespaces
     */
    protected function transformControllerNamespaces($content)
    {
        // Update use statements for models and requests
        $content = str_replace(
            'use admin\\products\\Models\\Product;',
            'use Modules\\Products\\app\\Models\\Product;',
            $content
        );

        $content = str_replace(
            'use admin\\products\\Requests\\ProductCreateRequest;',
            'use Modules\\Products\\app\\Http\\Requests\\ProductCreateRequest;',
            $content
        );

        $content = str_replace(
            'use admin\\products\\Requests\\ProductUpdateRequest;',
            'use Modules\\Products\\app\\Http\\Requests\\ProductUpdateRequest;',
            $content
        );

        return $content;
    }

    /**
     * Transform model-specific namespaces
     */
    protected function transformModelNamespaces($content)
    {
        // Any model-specific transformations
        return $content;
    }

    /**
     * Transform request-specific namespaces
     */
    protected function transformRequestNamespaces($content)
    {
        // Any request-specific transformations
        return $content;
    }

    /**
     * Transform route-specific namespaces
     */
    protected function transformRouteNamespaces($content)
    {
        // Update controller references in routes
        $content = str_replace(
            'admin\\products\\Controllers\\ProductManagerController',
            'Modules\\Products\\app\\Http\\Controllers\\Admin\\ProductManagerController',
            $content
        );

        return $content;
    }
}
