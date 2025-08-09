<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\ColorResource;
use App\Http\Resources\ImageResource;
use App\Http\Resources\PageResource;
use App\Http\Resources\ProductCardResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ReviewResource;
use App\Models\Category;
use App\Models\Page;
use App\Models\Product;
use App\Models\Review;
use Inertia\Inertia;

class PageController extends Controller
{
    public function home()
    {
        $page = Page::with('banners', 'metaTag')->where('type', 'home')->firstOrFail();
        $bestSeller = Product::activeInStock()
            ->variant()
            ->card()
            ->bestSeller()
            ->inRandomOrder()
            ->limit(14)
            ->get();
        $newProducts = Product::activeInStock()
            ->variant()
            ->card()
            ->inRandomOrder()
            ->limit(7)
            ->get();
        $banners = $page->banners->where('active', 1);
        $carousel_top = $banners->where('position', 'top')->where('type', 'carousel')->sortBy('sort');
        $banners_top = $banners->where('position', 'top')->where('type', 'banner');
        $banners_bottom = $banners->where('position', 'below');
        $categories = Category::active()->where('type', 'product')->where('in_home', 1)->get();
        $reviews = Review::all()->where('featured', 1);
        return Inertia::render('Home/Home', [
            'page' => new PageResource($page),
            'productsBestSeller' => ProductCardResource::collection($bestSeller),
            'newProducts' => ProductCardResource::collection($newProducts),
            'carouselTop' => ImageResource::collection($carousel_top),
            'bannersTop' => ImageResource::collection($banners_top),
            'bannersBottom' => ImageResource::collection($banners_bottom),
            'categoriesProductCount' => CategoryResource::collection($categories),
            'reviews' => ReviewResource::collection($reviews),
        ]);
    }

    public function offers()
    {
        $page = Page::with('banners', 'metaTag')->where('type', 'offers')->firstOrFail();
        $banners = $page->banners->where('active', 1);
        $banners_top = $banners->where('position', 'top')->where('type', 'banner');
        $offer_products = Product::activeInStock()->card()
            ->inOffer()->orderBy('offer', 'desc')->limit(16)->get();
        return Inertia::render('Offers/Offers', [
            'page' => new PageResource($page),
            'offerProducts' => ProductCardResource::collection($offer_products),
            'bannersTop' => ImageResource::collection($banners_top),
        ]);
    }

    public function contact()
    {
        $page = Page::with('metaTag')->where('type', 'contact')->firstOrFail();
        return Inertia::render('Contact/Contact', [
            'page' => new PageResource($page),
        ]);
    }

    public function product($slug, $ref)
    {
        $product = Product::where('slug', $slug)
            ->where('ref', $ref)
            ->variant()
            ->with('images', 'category', 'department', 'brand', 'specifications.specification_values', 'skus', 'metaTag')
            ->activeInstock()
            ->withSum('skus', 'stock')
            ->firstOrFail();
        $related_products = Product::activeInStock()
            ->card()
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->where('department_id', $product->department_id)
            ->inRandomOrder()->limit(12)->get();
        return Inertia::render('Product/Product', [
            'product' => new ProductResource($product),
            'relatedProducts' => ProductCardResource::collection($related_products),
        ]);
    }

    public function product_popup($slug, $ref)
    {
        $product = Product::where('slug', $slug)
            ->where('ref', $ref)
            ->variant()
            ->with('images', 'category', 'department', 'brand', 'specifications.specification_values', 'skus', 'metaTag')
            ->activeInstock()
            ->withSum('skus', 'stock')
            ->firstOrFail();
        $product = new ProductResource($product);
        return $product;
        // return redirect()->route('orders');
    }
}