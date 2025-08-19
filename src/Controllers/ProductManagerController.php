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
use admin\users\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use admin\admin_auth\Traits\HasSeo;

class ProductManagerController extends Controller
{
    use HasSeo;
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

            return view('product::admin.product.index', compact('products'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load products: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $seo = null;
            $products = [];
            $categories = Category::whereNull('parent_category_id')
                ->isActive()
                ->with('childrenRecursive')
                ->get();
            $nestedCategories = $this->buildNestedOptions($categories);
            $parentCategories = Category::where('parent_category_id', 0)->isActive()->pluck('title', 'id');
            $brands = Brand::isActive()->pluck('name', 'id');
            $tags = Tag::isActive()->pluck('name', 'id');
            $sellers = User::join('user_roles', 'users.role_id', '=', 'user_roles.id')
                ->where('user_roles.name', 'seller')
                ->where('users.status', 1)
                ->select('users.id', DB::raw("CONCAT(users.first_name, ' ', users.last_name) as name"))
                ->get();
            return view('product::admin.product.createOrEdit', [
                'categories' => $nestedCategories,
                'brands' => $brands,
                'tags' => $tags,
                'products' => $products,
                'sellers' => $sellers,
                'parentCategories' => $parentCategories,
                'seo' => $seo,
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
                'seller_id' => $data['seller_id'],
                'name' => $data['name'],
                'short_description' => $data['short_description'] ?? null,
                'description' => $data['description'] ?? null,
                'primary_category_id' => $data['primary_category_id'] ?? null,
                'brand_id' => $data['brand_id'] ?? null,
                'sku' => $data['sku'] ?? null,
                'barcode' => $data['barcode'] ?? null,
                'status' => $data['status'] ?? 'draft',
                'is_featured' => $data['is_featured'] ?? false,
                'published_at' => $data['published_at'] ?? null,
                'image_url' => $data['image_url'] ?? null,
                'alt_text' => $data['alt_text'] ?? null,
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
            $this->saveSeo(Product::class, $product->id, $data);
            // $product->seo()->create(array_merge(
            //     [
            //         'model_name' => 'Product',
            //         'model_record_id' => $product->id,
            //     ],
            //     [
            //         'meta_title' => $data['meta_title'] ?? null,
            //         'meta_description' => $data['meta_description'] ?? null,
            //         'meta_keywords' => $data['meta_keywords'] ?? null,
            //     ]
            // ));

            // 6.create product_tag
            if ($request->has('tag_ids')) {
                $request->merge(['product_id' => $product->id]);
                $product->tags()->sync($request->input('tag_ids', []));
            }

            // 7. Upload Images
            if (!empty($request->gallery_images)) {
                $this->storeGalleryImages($product, $request->gallery_images);
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
            $seo = $this->getSeo($product);
            $product->load([
                'tags',
                'categories',
                'images',
                // 'relatedProducts',
                'inventory',
                'prices',
                'shipping',
            ]);
            return view('product::admin.product.show', compact('product', 'seo'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load products: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $product = Product::with(['prices', 'inventory', 'shipping', 'seo', 'tags', 'images'])->findOrFail($id);
            $seo = $this->getSeo($product);
            // Prepare images for Dropzone
            $existingImages = [];
            $existingImages = $product->images->map(function ($image) {
                return [
                    'id'   => $image->id,
                    'name' => basename($image->gallery_image),
                    'url'  => Storage::url($image->gallery_image),
                ];
            });           

            $categories = Category::whereNull('parent_category_id')
                ->isActive()
                ->with('childrenRecursive')
                ->get();
            $nestedCategories = $this->buildNestedOptions($categories);

            $parentCategories = Category::where('parent_category_id', 0)->isActive()->pluck('title', 'id');
            $brands = Brand::isActive()->pluck('name', 'id');
            $tags = Tag::isActive()->pluck('name', 'id');
            $sellers = User::join('user_roles', 'users.role_id', '=', 'user_roles.id')
                ->where('user_roles.name', 'seller')
                ->where('users.status', 1)
                ->select('users.id', DB::raw("CONCAT(users.first_name, ' ', users.last_name) as name"))
                ->get();


            return view('product::admin.product.createOrEdit', [
                'product' => $product,
                'categories' => $nestedCategories,
                'brands' => $brands,
                'tags' => $tags,
                'parentCategories' => $parentCategories,
                'sellers' => $sellers,
                'existingImages' => $existingImages, 
                'seo' => $seo, 
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
                'seller_id' => $data['seller_id'],
                'name' => $data['name'],
                'short_description' => $data['short_description'] ?? null,
                'description' => $data['description'] ?? null,
                'primary_category_id' => $data['primary_category_id'] ?? null,
                // 'subcategory_id' => $data['subcategory_id'] ?? null,
                'brand_id' => $data['brand_id'] ?? null,
                'sku' => $data['sku'] ?? null,
                'barcode' => $data['barcode'] ?? null,
                'status' => $data['status'] ?? 'draft',
                'is_featured' => $data['is_featured'] ?? false,
                'published_at' => $data['published_at'] ?? null,
                'image_url' => $data['image_url'] ?? null,
                'alt_text' => $data['alt_text'] ?? null,
            ]);

            if ($request->has('subcategory_ids')) {
                $request->merge(['product_id' => $product->id]);
                $product->categories()->sync($request->input('subcategory_ids', []));
            }


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
             $this->saveSeo(Product::class, $product->id, $data);
            // $product->seo()->updateOrCreate(
            //     ['model_record_id' => $product->id, 'model_name' => 'Product'],
            //     [
            //         'meta_title' => $data['meta_title'] ?? null,
            //         'meta_description' => $data['meta_description'] ?? null,
            //         'meta_keywords' => $data['meta_keywords'] ?? null,
            //     ]
            // );

            // 6. Update Tags
            $product->tags()->sync($request->input('tag_ids', []));


            // 7. Update Images
            if (!empty($request->gallery_images)) {
                $this->storeGalleryImages($product, $request->gallery_images);
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

    public function getChildren(Request $request)
    {
        $parentId = $request->input('parent_id');

        $children = Category::where('parent_category_id', $parentId)
            ->isActive()
            ->select('id', 'title') // Important for JS
            ->get();

        return response()->json($children);
    }

    private function buildNestedOptions($categories, $prefix = '')
    {
        $options = [];

        foreach ($categories as $category) {
            $options[] = [
                'id' => $category->id,
                'name' => $prefix . $category->name
            ];

            if ($category->childrenRecursive->isNotEmpty()) {
                $children = $this->buildNestedOptions($category->childrenRecursive, $prefix . '— ');
                $options = array_merge($options, $children);
            }
        }

        return $options;
    }

    public function getSubcategories($id)
    {
        $subcategories = Category::where('parent_category_id', $id)->isActive()->select('id', 'title')->get();

        return response()->json($subcategories);
    }

    public function getSubcategoriesWithChildren($parentCategoryId)
    {
        $subcategories = Category::where('parent_category_id', $parentCategoryId)
            ->isActive()
            ->with('children:id,parent_category_id,title')
            ->get(['id', 'title', 'parent_category_id']);

        return response()->json($subcategories);
    }

    private function storeGalleryImages($product, $images)
    {
        foreach ($images as $imageData) {
            // Support both base64 and file upload array
            if (is_array($imageData) && isset($imageData['base64'])) {
                $base64 = $imageData['base64'];
                if (preg_match('/data:image\/(\w+);base64,/', $base64, $type)) {
                    $image = substr($base64, strpos($base64, ',') + 1);
                    $image = base64_decode($image);
                    $extension = $type[1];
                    $filename = 'products/' . uniqid('', true) . '.' . $extension;

                    Storage::disk('public')->put($filename, $image);

                    ProductImage::create([
                        'product_id' => $product->id,
                        'gallery_image' => $filename,
                    ]);
                }
            } elseif ($imageData instanceof \Illuminate\Http\UploadedFile) {
                $filename = $imageData->store('products', 'public');

                ProductImage::create([
                    'product_id' => $product->id,
                    'gallery_image' => $filename,
                ]);
            } elseif (is_array($imageData) && isset($imageData['file']) && $imageData['file'] instanceof \Illuminate\Http\UploadedFile) {
                $file = $imageData['file'];
                $filename = $file->store('products', 'public');

                ProductImage::create([
                    'product_id' => $product->id,
                    'gallery_image' => $filename,
                ]);
            }
        }
    }

    public function deleteImage($id)
    {
        $image = ProductImage::findOrFail($id);

        // Delete file from storage
        if ($image->gallery_image && Storage::disk('public')->exists($image->gallery_image)) {
            Storage::disk('public')->delete($image->gallery_image);
        }

        $image->delete();

        return response()->json(['success' => true]);
    }
}
