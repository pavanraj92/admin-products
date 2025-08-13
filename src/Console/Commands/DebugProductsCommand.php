<?php

namespace admin\products\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class DebugProductsCommand extends Command
{
    protected $signature = 'products:debug';
    protected $description = 'Debug Products module routing and view resolution';

    public function handle()
    {
        $this->info('🔍 Debugging Products Module...');

        // Check route file loading
        $this->info("\n📍 Route Files:");
        $moduleRoutes = base_path('Modules/Products/routes/web.php');
        if (File::exists($moduleRoutes)) {
            $this->info("✅ Module routes found: {$moduleRoutes}");
            $this->info("   Last modified: " . date('Y-m-d H:i:s', filemtime($moduleRoutes)));
        } else {
            $this->error("❌ Module routes not found");
        }

        $packageRoutes = base_path('packages/admin/products/src/routes/web.php');
        if (File::exists($packageRoutes)) {
            $this->info("✅ Package routes found: {$packageRoutes}");
            $this->info("   Last modified: " . date('Y-m-d H:i:s', filemtime($packageRoutes)));
        } else {
            $this->error("❌ Package routes not found");
        }
        
        // Check view loading priority
        $this->info("\n👀 View Loading Priority:");
        $viewPaths = [
            'Module views' => base_path('Modules/Products/resources/views'),
            'Published views' => resource_path('views/admin/products'),
            'Package views' => base_path('packages/admin/products/resources/views'),
        ];
        
        foreach ($viewPaths as $name => $path) {
            if (File::exists($path)) {
                $this->info("✅ {$name}: {$path}");
            } else {
                $this->warn("⚠️  {$name}: NOT FOUND - {$path}");
            }
        }
        
        // Check controller resolution
        $this->info("\n🎯 Controller Resolution:");
       $controllers = [
            'ProductManagerController' => 'Modules\\Products\\app\\Http\\Controllers\\Admin\\ProductManagerController',
            'OrderManagerController' => 'Modules\\Products\\app\\Http\\Controllers\\Admin\\OrderManagerController',
            'ReturnRefundManagerController' => 'Modules\\Products\\app\\Http\\Controllers\\Admin\\ReturnRefundManagerController',
            'TransactionManagerController' => 'Modules\\Products\\app\\Http\\Controllers\\Admin\\TransactionManagerController',
        ];

         foreach ($controllers as $label => $controllerClass) {
            $this->info("Checking {$label}: {$controllerClass}");
            if (class_exists($controllerClass)) {
            $this->info("✅ Controller class found: {$controllerClass}");
            $reflection = new \ReflectionClass($controllerClass);
            $this->info("   File: " . $reflection->getFileName());
            $this->info("   Last modified: " . date('Y-m-d H:i:s', filemtime($reflection->getFileName())));
            } else {
            $this->error("❌ Controller class not found: {$controllerClass}");
            }
        }

       // Show current routes
        $this->info("\n🛣️  Current Routes:");
        $routes = Route::getRoutes();
        $productRoutes = [];

        foreach ($routes as $route) {
            $action = $route->getAction();
            if (isset($action['controller'])) {
            if (
                str_contains($action['controller'], 'ProductManagerController') ||
                str_contains($action['controller'], 'OrderManagerController')   ||
                str_contains($action['controller'], 'ReturnRefundManagerController') ||
                str_contains($action['controller'], 'TransactionManagerController')
            ) {
                $productRoutes[] = [
                'uri' => $route->uri(),
                'methods' => implode('|', $route->methods()),
                'controller' => $action['controller'],
                'name' => $route->getName(),
                ];
            }
            }
        }
        
        if (!empty($productRoutes)) {
            $this->table(['URI', 'Methods', 'Controller', 'Name'], $productRoutes);
        } else {
            $this->warn("No shipping routes found.");
        }
    }
}
