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

            // Models
            $basePath . '/Models/Product.php' => base_path('Modules/Products/app/Models/Product.php'),

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
        ];

        // Apply transformations
        foreach ($namespaceTransforms as $search => $replace) {
            $content = str_replace($search, $replace, $content);
        }

        // Handle specific file types
        if (str_contains($sourceFile, 'Controllers')) {
            $content = str_replace('use admin\\products\\Models\\Product;', 'use Modules\\Products\\app\\Models\\Product;', $content);
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
