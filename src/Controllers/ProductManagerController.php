<?php

namespace admin\products\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use admin\products\Requests\ProductCreateRequest;
use admin\products\Requests\ProductUpdateRequest;
use admin\products\Models\Product;
use admin\admin_auth\Services\ImageService;
use admin\brands\Models\Brand;
use admin\categories\Models\Category;
use admin\products\Models\ProductImage;
use admin\tags\Models\Tag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductManagerController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
        $this->middleware('admincan_permission:products_manager_list')->only(['index']);
        $this->middleware('admincan_permission:products_manager_create')->only(['create', 'store']);
        $this->middleware('admincan_permission:products_manager_edit')->only(['edit', 'update']);
        $this->middleware('admincan_permission:products_manager_view')->only(['show']);
        $this->middleware('admincan_permission:products_manager_delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $products = Product::filter($request->query('keyword'))
                ->filterByStatus($request->query('status'))
                ->sortable()
                ->latest()
                ->paginate(Product::getPerPageLimit())
                ->withQueryString();

            return view('product::admin.index', compact('products'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load products: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $products = [];
            $categories = Category::whereNull('parent_category_id')
                ->with('childrenRecursive')
                ->get();
            $nestedCategories = $this->buildNestedOptions($categories);
            $parentCategories = Category::where('parent_category_id', 0)->pluck('title', 'id');
            $brands = Brand::pluck('name', 'id');
            $tags = Tag::pluck('name', 'id');
            return view('product::admin.createOrEdit', [
                'categories' => $nestedCategories,
                'brands' => $brands,
                'tags' => $tags,
                'products' => $products,
                'parentCategories' => $parentCategories,
            ]);
            // return view('product::admin.createOrEdit', compact('categories', 'brands', 'tags', 'products', 'nestedCategories'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load products: ' . $e->getMessage());
        }
    }

    public function store(ProductCreateRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();

            $product = Product::create([
                'name' => $data['name'],
                'short_description' => $data['short_description'] ?? null,
                'description' => $data['description'] ?? null,
                'brand_id' => $data['brand_id'] ?? null,
                'sku' => $data['sku'] ?? null,
                'barcode' => $data['barcode'] ?? null,
                'status' => $data['status'] ?? 'draft',
                'is_featured' => $data['is_featured'] ?? false,
                'published_at' => $data['published_at'] ?? null,
            ]);

            // 1. Create product_category
            if ($request->has('subcategory_ids')) {
                $request->merge(['product_id' => $product->id]);
                $product->categories()->sync($request->input('subcategory_ids', []));
            }

            // 2. Create Pricing
            $product->prices()->create([
                'product_id'    => $product->id,
                'regular_price' => $data['regular_price'] ?? 0,
                'sale_price'    => $data['sale_price'] ?? 0,
                'cost_price'    => $data['cost_price'] ?? 0,
                'tax_class'     => $data['tax_class'] ?? null,
                'tax_rate'      => $data['tax_rate'] ?? null,
            ]);

            // 3. Create inventory
            $product->inventory()->create([
                'product_id'         => $product->id,
                'stock_quantity'     => $data['stock_quantity'] ?? 0,
                'low_stock_threshold' => $data['low_stock_threshold'] ?? null,
                'stock_status'       => $data['stock_status'] ?? 'in_stock',
            ]);

            // 4. Create product shipping
            $product->shipping()->create([
                'product_id'        => $product->id,
                'weight'            => $data['weight'] ?? null,
                'length'            => $data['length'] ?? null,
                'width'             => $data['width'] ?? null,
                'height'            => $data['height'] ?? null,
                'shipping_class'    => $data['shipping_class'] ?? null,
                'requires_shipping' => $data['requires_shipping'] ?? false,
            ]);

            // 5. Create SEO
            $product->seo()->create(array_merge(
                [
                    'model_name' => 'Product',
                    'model_record_id' => $product->id,
                ],
                [
                    'meta_title' => $data['meta_title'] ?? null,
                    'meta_description' => $data['meta_description'] ?? null,
                    'meta_keywords' => $data['meta_keywords'] ?? null,
                ]
            ));

            // 6.create product_tag
            if ($request->has('tag_ids')) {
                $request->merge(['product_id' => $product->id]);
                $product->tags()->sync($request->input('tag_ids', []));
            }

            // 7. Upload Images
            if ($request->filled('gallery_images')) {
                $images = json_decode($request->gallery_images, true);

                foreach ($images as $imageData) {
                    if (!isset($imageData['base64'])) continue;

                    $base64 = $imageData['base64'];

                    // Extract base64 content
                    if (preg_match('/data:image\/(\w+);base64,/', $base64, $type)) {
                        $image = substr($base64, strpos($base64, ',') + 1);
                        $image = base64_decode($image);
                        $extension = $type[1];
                        $filename = 'products/' . uniqid() . '.' . $extension;

                        // Save to storage
                        Storage::disk('public')->put($filename, $image);

                        // Save to DB
                        ProductImage::create([
                            'product_id' => $product->id,
                            'image_url' => $filename,
                            'alt_text' => $imageData['alt_text'] ?? '', // enhance later
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create product: ' . $e->getMessage());
        }
    }

    /**
     * show product details
     */
    public function show(Product $product)
    {
        try {
            $product->load([
                'tags',
                'categories',
                'images',
                'relatedProducts',
                'inventory',
                'prices',
                'shipping',
            ]);
            return view('product::admin.show', compact('product'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load products: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $product = Product::with(['prices', 'inventory', 'shipping', 'seo', 'tags', 'images'])->findOrFail($id);

            $categories = Category::whereNull('parent_category_id')
                ->with('childrenRecursive')
                ->get();
            $nestedCategories = $this->buildNestedOptions($categories);

            $parentCategories = Category::where('parent_category_id', 0)->pluck('title', 'id');
            $brands = Brand::pluck('name', 'id');
            $tags = Tag::pluck('name', 'id');

            return view('product::admin.createOrEdit', [
                'product' => $product,
                'categories' => $nestedCategories,
                'brands' => $brands,
                'tags' => $tags,
                'parentCategories' => $parentCategories,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load product: ' . $e->getMessage());
        }
    }


    public function update(ProductUpdateRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            // dd($data);
            $product = Product::findOrFail($id);

            // 1. Update main product info
            $product->update([
                'name' => $data['name'],
                'short_description' => $data['short_description'] ?? null,
                'description' => $data['description'] ?? null,
                'subcategory_id' => $data['subcategory_id'] ?? null,
                'brand_id' => $data['brand_id'] ?? null,
                'sku' => $data['sku'] ?? null,
                'barcode' => $data['barcode'] ?? null,
                'status' => $data['status'] ?? 'draft',
                'is_featured' => $data['is_featured'] ?? false,
                'published_at' => $data['published_at'] ?? null,
            ]);

            // 2. Update pricing
            $product->prices()->updateOrCreate(
                ['product_id' => $product->id],
                [
                    'regular_price' => $data['regular_price'] ?? 0,
                    'sale_price' => $data['sale_price'] ?? 0,
                    'cost_price' => $data['cost_price'] ?? 0,
                    'tax_class' => $data['tax_class'] ?? null,
                    'tax_rate' => $data['tax_rate'] ?? null,
                ]
            );

            // 3. Update inventory
            $product->inventory()->updateOrCreate(
                ['product_id' => $product->id],
                [
                    'stock_quantity' => $data['stock_quantity'] ?? 0,
                    'low_stock_threshold' => $data['low_stock_threshold'] ?? null,
                    'stock_status' => $data['stock_status'] ?? 'in_stock',
                ]
            );

            // 4. Update shipping
            $product->shipping()->updateOrCreate(
                ['product_id' => $product->id],
                [
                    'weight' => $data['weight'] ?? null,
                    'length' => $data['length'] ?? null,
                    'width' => $data['width'] ?? null,
                    'height' => $data['height'] ?? null,
                    'shipping_class' => $data['shipping_class'] ?? null,
                    'requires_shipping' => $data['requires_shipping'] ?? false,
                ]
            );

            // 5. Update SEO
            $product->seo()->updateOrCreate(
                ['model_record_id' => $product->id, 'model_name' => 'Product'],
                [
                    'meta_title' => $data['meta_title'] ?? null,
                    'meta_description' => $data['meta_description'] ?? null,
                    'meta_keywords' => $data['meta_keywords'] ?? null,
                ]
            );

            // 6. Update Tags
            $product->tags()->sync($request->input('tag_ids', []));

            // 7. Update Images (optional: remove old ones or append new)
            if ($request->filled('gallery_images')) {
                $images = json_decode($request->gallery_images, true);

                foreach ($images as $imageData) {
                    if (!isset($imageData['base64'])) continue;

                    $base64 = $imageData['base64'];

                    if (preg_match('/data:image\/(\w+);base64,/', $base64, $type)) {
                        $image = substr($base64, strpos($base64, ',') + 1);
                        $image = base64_decode($image);
                        $extension = $type[1];
                        $filename = 'products/' . uniqid() . '.' . $extension;

                        Storage::disk('public')->put($filename, $image);

                        ProductImage::create([
                            'product_id' => $product->id,
                            'image_url' => $filename,
                            'alt_text' => $imageData['alt_text'] ?? '',
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update product: ' . $e->getMessage());
        }
    }

    public function destroy(Product $product)
    {
        DB::beginTransaction();
        try {
            foreach ($product->images as $img) {
                Storage::disk('public')->delete($img->image_url);
                $img->delete();
            }
            $product->delete();
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Product deleted successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to delete product.', 'error' => $e->getMessage()], 500);
        }
    }

    public function getSubcategories($id)
    {
        $subcategories = Category::where('parent_category_id', $id)->select('id', 'title')->get();

        return response()->json($subcategories);
    }

    public function getSubcategoriesWithChildren($parentCategoryId)
    {
        $subcategories = Category::where('parent_category_id', $parentCategoryId)
            ->with('children:id,parent_category_id,title')
            ->get(['id', 'title', 'parent_category_id']);

        return response()->json($subcategories);
    }
}
