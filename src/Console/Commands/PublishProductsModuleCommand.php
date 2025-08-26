<?php

namespace admin\products\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PublishProductsModuleCommand extends Command
{
    protected $signature = 'products:publish {--force : Force overwrite existing files}';
    protected $description = 'Publish Products module files with proper namespace transformation';

    public function handle()
    {
        $this->info('Publishing Products module files...');

        // Check if module directory exists
        $moduleDir = base_path('Modules/Products');
        if (!File::exists($moduleDir)) {
            File::makeDirectory($moduleDir, 0755, true);
        }

        // Publish with namespace transformation
        $this->publishWithNamespaceTransformation();
        
        // Publish other files
        $this->call('vendor:publish', [
            '--tag' => 'product',
            '--force' => $this->option('force')
        ]);

        // Update composer autoload
        $this->updateComposerAutoload();

        $this->info('Products module published successfully!');
        $this->info('Please run: composer dump-autoload');
    }

    protected function publishWithNamespaceTransformation()
    {
        $basePath = dirname(dirname(__DIR__)); // Go up to packages/admin/products/src

        $filesWithNamespaces = [
            // Controllers
            $basePath . '/Controllers/ProductManagerController.php' => base_path('Modules/Products/app/Http/Controllers/Admin/ProductManagerController.php'),
            $basePath . '/Controllers/OrderManagerController.php' => base_path('Modules/Products/app/Http/Controllers/Admin/OrderManagerController.php'),

            // Models
            $basePath . '/Models/Product.php' => base_path('Modules/Products/app/Models/Product.php'),
            $basePath . '/Models/ProductCategory.php' => base_path('Modules/Products/app/Models/ProductCategory.php'),
            $basePath . '/Models/ProductImage.php' => base_path('Modules/Products/app/Models/ProductImage.php'),
            $basePath . '/Models/ProductInventory.php' => base_path('Modules/Products/app/Models/ProductInventory.php'),
            $basePath . '/Models/ProductPrice.php' => base_path('Modules/Products/app/Models/ProductPrice.php'),
            $basePath . '/Models/ProductShipping.php' => base_path('Modules/Products/app/Models/ProductShipping.php'),
            $basePath . '/Models/ProductTag.php' => base_path('Modules/Products/app/Models/ProductTag.php'),
            $basePath . '/Models/Order.php' => base_path('Modules/Products/app/Models/Order.php'),
            $basePath . '/Models/OrderAddress.php' => base_path('Modules/Products/app/Models/OrderAddress.php'),
            $basePath . '/Models/OrderItem.php' => base_path('Modules/Products/app/Models/OrderItem.php'),

            // Requests
            $basePath . '/Requests/ProductCreateRequest.php' => base_path('Modules/Products/app/Http/Requests/ProductCreateRequest.php'),
            $basePath . '/Requests/ProductUpdateRequest.php' => base_path('Modules/Products/app/Http/Requests/ProductUpdateRequest.php'),

            // Routes
            $basePath . '/routes/web.php' => base_path('Modules/Products/routes/web.php'),
        ];

        foreach ($filesWithNamespaces as $source => $destination) {
            if (File::exists($source)) {
                File::ensureDirectoryExists(dirname($destination));
                
                $content = File::get($source);
                $content = $this->transformNamespaces($content, $source);
                
                File::put($destination, $content);
                $this->info("Published: " . basename($destination));
            } else {
                $this->warn("Source file not found: " . $source);
            }
        }
    }

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
            'admin\\products\\Controllers\\OrderManagerController' => 'Modules\\Products\\app\\Http\\Controllers\\Admin\\OrderManagerController',
        ];

        // Apply transformations
        foreach ($namespaceTransforms as $search => $replace) {
            $content = str_replace($search, $replace, $content);
        }

        // Handle specific file types
        if (str_contains($sourceFile, 'Controllers')) {
            $content = str_replace(
                'use admin\\products\\Models\\Product;',
                'use Modules\\Products\\app\\Models\\Product;',
                $content
            );
            $content = str_replace(
                'use admin\\products\\Models\\Order;',
                'use Modules\\Products\\app\\Models\\Order;',
                $content
            );
            $content = str_replace(
                'use admin\\products\\Models\\OrderAddress;',
                'use Modules\\Products\\app\\Models\\OrderAddress;',
                $content
            );
            $content = str_replace(
                'use admin\\products\\Models\\OrderItem;',
                'use Modules\\Products\\app\\Models\\OrderItem;',
                $content
            );
            $content = str_replace(
                'use admin\\products\\Models\\ProductCategory;',
                'use Modules\\Products\\app\\Models\\ProductCategory;',
                $content
            );
            $content = str_replace(
                'use admin\\products\\Models\\ProductImage;',
                'use Modules\\Products\\app\\Models\\ProductImage;',
                $content
            );
            $content = str_replace(
                'use admin\\products\\Models\\ProductInventory;',
                'use Modules\\Products\\app\\Models\\ProductInventory;',
                $content
            );
            $content = str_replace(
                'use admin\\products\\Models\\ProductPrice;',
                'use Modules\\Products\\app\\Models\\ProductPrice;',
                $content
            );
            $content = str_replace(
                'use admin\\products\\Models\\ProductShipping;',
                'use Modules\\Products\\app\\Models\\ProductShipping;',
                $content
            );
            $content = str_replace(
                'use admin\\products\\Models\\ProductTag;',
                'use Modules\\Products\\app\\Models\\ProductTag;',
                $content
            );
           
            $content = str_replace('use admin\\products\\Requests\\ProductCreateRequest;', 'use Modules\\Products\\app\\Http\\Requests\\ProductCreateRequest;', $content);
            $content = str_replace('use admin\\products\\Requests\\ProductUpdateRequest;', 'use Modules\\Products\\app\\Http\\Requests\\ProductUpdateRequest;', $content);
        }

        return $content;
    }

    protected function updateComposerAutoload()
    {
        $composerFile = base_path('composer.json');
        $composer = json_decode(File::get($composerFile), true);

        // Add module namespace to autoload
        if (!isset($composer['autoload']['psr-4']['Modules\\Products\\'])) {
            $composer['autoload']['psr-4']['Modules\\Products\\'] = 'Modules/Products/app/';

            File::put($composerFile, json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            $this->info('Updated composer.json autoload');
        }
    }
}
