<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\DepartmentResource;
use App\Http\Resources\ProductCardResource;
use App\Models\Category;
use App\Models\Department;
use App\Models\Product;
use Inertia\Inertia;
use App\Http\Resources\ImageResource;
use App\Models\Page;

class DepartmentController extends Controller
{
    public function department($department)
    {
        $department = Department::active()->with('metaTag')->where('slug', $department)->firstOrFail();
        $page = Page::with('banners', 'metaTag')->where('type', 'home')->firstOrFail();
        $banners_medium = $page->banners
            ->where('active', 1)
            ->where('position', 'middle');

        $offers_product = Product::variant()
            ->where('department_id', $department->id)
            ->card()
            ->activeInStock()
            ->inOffer()
            ->limit(14)
            ->inRandomOrder()
            ->get();

        $best_sellers_product = Product::variant()
            ->where('department_id', $department->id)
            ->card()
            ->activeInStock()
            ->inOffer()
            ->limit(10)
            ->inRandomOrder()
            ->get();

        $categories = Category::active()
            ->withWhereHas('products', function ($query) use ($department) {
                $query->variant()->card()->activeInStock()->inRandomOrder()->where('department_id', $department->id)->limit(10);
            })->get();

        return Inertia::render('Department/Department', [
            'department' => new DepartmentResource($department),
            'offertProducts' => ProductCardResource::collection($offers_product),
            'bestSellersProducts' => ProductCardResource::collection($best_sellers_product),
            'categories' => CategoryResource::collection($categories),
            'bannersMedium' => ImageResource::collection($banners_medium),
        ]);
    }
}
