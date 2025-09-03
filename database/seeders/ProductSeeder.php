<?php

namespace Admin\Products\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use admin\products\Models\Product;
use Carbon\Carbon;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $products = [
            [
                'seller_id' => 2,
                'name' => 'Jackets',
                'short_description' => 'Short description for Jackets',
                'description' => 'Full description for Jackets',
                'primary_category_id' => 1,
                'brand_id' => 1,
                'sku' => 'SKU001',
                'barcode' => '111111111111',
                'status' => 'published',
                'published_at' => $now,
                'prices' => [
                    'regular_price' => 100,
                    'sale_price' => 90,
                    'cost_price' => 70,
                    'tax_class' => 'standard',
                    'tax_rate' => 10,
                ],
                'shipping' => [
                    'weight' => 1.5,
                    'length' => 10,
                    'width' => 5,
                    'height' => 3,
                    'shipping_class' => 'standard',
                    'requires_shipping' => true,
                ],
                'category_ids' => [2, 3],
                'seo' => [
                    'meta_title' => 'Jackets SEO Title',
                    'meta_description' => 'Jackets SEO Description',
                    'meta_keywords' => 'jackets, winter, men, women',
                ],
            ],
            [
                'seller_id' => 3,
                'name' => 'Raincoats',
                'short_description' => 'Short description for Raincoats',
                'description' => 'Full description for Raincoats',
                'primary_category_id' => 1,
                'brand_id' => 2,
                'sku' => 'SKU002',
                'barcode' => '222222222222',
                'status' => 'published',
                'published_at' => $now,
                'prices' => [
                    'regular_price' => 200,
                    'sale_price' => 180,
                    'cost_price' => 150,
                    'tax_class' => 'standard',
                    'tax_rate' => 12,
                ],
                'shipping' => [
                    'weight' => 2.0,
                    'length' => 12,
                    'width' => 6,
                    'height' => 4,
                    'shipping_class' => 'express',
                    'requires_shipping' => true,
                ],
                'category_ids' => [2, 3],
                'seo' => [
                    'meta_title' => 'Raincoats SEO Title',
                    'meta_description' => 'Raincoats SEO Description',
                    'meta_keywords' => 'raincoats, men, women, waterproof',
                ],
            ],
            [
                'seller_id' => 2,
                'name' => 'Dresses',
                'short_description' => 'Short description for Dresses',
                'description' => 'Full description for Dresses',
                'primary_category_id' => 1,
                'brand_id' => 3,
                'sku' => 'SKU003',
                'barcode' => '333333333333',
                'status' => 'draft',
                'published_at' => null,
                'prices' => [
                    'regular_price' => 300,
                    'sale_price' => 270,
                    'cost_price' => 200,
                    'tax_class' => 'reduced',
                    'tax_rate' => 8,
                ],
                'shipping' => [
                    'weight' => 2.5,
                    'length' => 15,
                    'width' => 7,
                    'height' => 5,
                    'shipping_class' => 'standard',
                    'requires_shipping' => true,
                ],
                'category_ids' => [4],
                'seo' => [
                    'meta_title' => 'Dresses SEO Title',
                    'meta_description' => 'Dresses SEO Description',
                    'meta_keywords' => 'dresses, kids, summer',
                ],
            ],
        ];

        foreach ($products as $data) {
            DB::beginTransaction();
            try {
                $product = Product::updateOrCreate(
                    ['sku' => $data['sku']],
                    [
                        'seller_id' => $data['seller_id'],
                        'name' => $data['name'],
                        'short_description' => $data['short_description'] ?? null,
                        'description' => $data['description'] ?? null,
                        'primary_category_id' => $data['primary_category_id'] ?? null,
                        'brand_id' => $data['brand_id'] ?? null,
                        'sku' => $data['sku'] ?? null,
                        'barcode' => $data['barcode'] ?? null,
                        'status' => $data['status'] ?? 'draft',
                        'published_at' => $data['published_at'] ?? null,
                    ]
                );

                // Categories
                if (!empty($data['category_ids'])) {
                    $product->categories()->sync($data['category_ids']);
                }

                // Prices
                if (!empty($data['prices'])) {
                    $product->prices()->updateOrCreate(
                        ['product_id' => $product->id],
                        $data['prices'] + ['product_id' => $product->id]
                    );
                }

                // Shipping
                if (!empty($data['shipping'])) {
                    $product->shipping()->updateOrCreate(
                        ['product_id' => $product->id],
                        $data['shipping'] + ['product_id' => $product->id]
                    );
                }

                // SEO
                if (!empty($data['seo'])) {
                    $product->seo()->updateOrCreate(
                        ['model_record_id' => $product->id],
                        array_merge(['model_name' => Product::class, 'model_record_id' => $product->id], $data['seo'])
                    );
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $this->command->error('Failed seeding product '.$data['name'].': '.$e->getMessage());
            }
        }
    }
}
