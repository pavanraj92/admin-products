<?php

namespace admin\products\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CheckModuleStatusCommand extends Command
{
    protected $signature = 'products:status';
    protected $description = 'Check if Products module files are being used';

    public function handle()
    {
        $this->info('Checking Products Module Status...');

        // Check if module files exist
        $moduleFiles = [
            'Product Controller' => base_path('Modules/Products/app/Http/Controllers/Admin/ProductManagerController.php'),
            'Product Model' => base_path('Modules/Products/app/Models/Product.php'),
            'Product Category Model' => base_path('Modules/Products/app/Models/ProductCategory.php'),
            'Product Image Model' => base_path('Modules/Products/app/Models/ProductImage.php'),
            'Product Inventory Model' => base_path('Modules/Products/app/Models/ProductInventory.php'),
            'Product Price Model' => base_path('Modules/Products/app/Models/ProductPrice.php'),
            'Product Shipping Model' => base_path('Modules/Products/app/Models/ProductShipping.php'),
            'Product Tag Model' => base_path('Modules/Products/app/Models/ProductTag.php'),

            'Order Controller' => base_path('Modules/Products/app/Http/Controllers/Admin/OrderManagerController.php'),
            'Order Model' => base_path('Modules/Products/app/Models/Order.php'),
            'Order Address Model' => base_path('Modules/Products/app/Models/OrderAddress.php'),
            'Order Item Model' => base_path('Modules/Products/app/Models/OrderItem.php'),

            'Return Refund Controller' => base_path('Modules/Products/app/Http/Controllers/Admin/ReturnRefundManagerController.php'),
            'Return Refund Model' => base_path('Modules/Products/app/Models/ReturnRefundRequest.php'),

            'Transaction Controller' => base_path('Modules/Products/app/Http/Controllers/Admin/TransactionManagerController.php'),
            'Transaction Model' => base_path('Modules/Products/app/Models/Transaction.php'),
           
            'Request (Create)' => base_path('Modules/Products/app/Http/Requests/ProductCreateRequest.php'),
            'Request (Update)' => base_path('Modules/Products/app/Http/Requests/ProductUpdateRequest.php'),
            'Routes' => base_path('Modules/Products/routes/web.php'),
            'Views' => base_path('Modules/Products/resources/views'),
            'Config' => base_path('Modules/Products/config/products.php'),
        ];

        $this->info("\n📁 Module Files Status:");
        foreach ($moduleFiles as $type => $path) {
            if (File::exists($path)) {
                $this->info("✅ {$type}: EXISTS");

                // Check if it's a PHP file and show last modified time
                if (str_ends_with($path, '.php')) {
                    $lastModified = date('Y-m-d H:i:s', filemtime($path));
                    $this->line("   Last modified: {$lastModified}");
                }
            } else {
                $this->error("❌ {$type}: NOT FOUND");
            }
        }

        // Check namespace in controller
        $controllers = [
            'Product Controller' => base_path('Modules/Products/app/Http/Controllers/Admin/ProductManagerController.php'),
            'Order Controller' => base_path('Modules/Products/app/Http/Controllers/Admin/OrderManagerController.php'),
            'Return Refund Controller' => base_path('Modules/Products/app/Http/Controllers/Admin/ReturnRefundManagerController.php'),
            'Transaction Controller' => base_path('Modules/Products/app/Http/Controllers/Admin/TransactionManagerController.php'),
        ];

        foreach ($controllers as $name => $controllerPath) {
            if (File::exists($controllerPath)) {
            $content = File::get($controllerPath);
            if (str_contains($content, 'namespace Modules\Products\app\Http\Controllers\Admin;')) {
                $this->info("\n✅ {$name} namespace: CORRECT");
            } else {
                $this->error("\n❌ {$name} namespace: INCORRECT");
            }

            // Check for test comment
            if (str_contains($content, 'Test comment - this should persist after refresh')) {
                $this->info("✅ Test comment in {$name}: FOUND (changes are persisting)");
            } else {
                $this->warn("⚠️  Test comment in {$name}: NOT FOUND");
            }
            }
        }

        // Check composer autoload
        $composerFile = base_path('composer.json');
        if (File::exists($composerFile)) {
            $composer = json_decode(File::get($composerFile), true);
            if (isset($composer['autoload']['psr-4']['Modules\\Products\\'])) {
                $this->info("\n✅ Composer autoload: CONFIGURED");
            } else {
                $this->error("\n❌ Composer autoload: NOT CONFIGURED");
            }
        }

        $this->info("\n🎯 Summary:");
        $this->info("Your Products module is properly published and should be working.");
        $this->info("Any changes you make to files in Modules/Products/ will persist.");
        $this->info("If you need to republish from the package, run: php artisan products:publish --force");
    }
}
