<?php

namespace App\Http\Controllers;

use App\Http\Resources\ColorResource;
use App\Http\Resources\PageResource;
use App\Http\Resources\ProductCardResource;
use App\Http\Resources\Search\CategoryFilterResource;
use App\Http\Resources\SizeResource;
use App\Models\Category;
use App\Models\Color;
use App\Models\Department;
use App\Models\Page;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $page = Page::with('banners', 'metaTag')->where('type', 'search')->firstOrFail();

        $banner = $page->banners->where('position', 'middle')->where('type', 'banner');

        $filters = [
            'q' => $request->input('q', null),
            'departments' => $request->input('departments', []),
            'categories' => $request->input('categories', []),
            'colors' => $request->input('colors', []),
            'price_min' => $request->input('price_min', ''),
            'price_max' => $request->input('price_max', ''),
            'offer' => $request->input('offer', ''),
            'sortBy' => $request->input('sortBy', ''),
            'attributes' => $request->input('attributes', []),
        ];

        $listDepartments = Department::active()->whereHas(
            'products',
            function ($query) use ($filters) {
                $query->withFilters([
                    ...$filters,
                    'departments' => []
                ]);
            }
        )->get();

        $listCategories = Category::active()->whereHas(
            'products',
            function ($query) use ($filters) {
                $query->withFilters([
                    ...$filters,
                    'categories' => []
                ]);
            }
        )->get();

        $listColors = Color::whereHas(
            'products',
            function ($query) use ($filters) {
                $query->withFilters([
                    ...$filters,
                    'colors' => []
                ]);
            }
        )->get();

        $products = Product::variant()->card()->withFilters($filters)->paginate(20)->withQueryString();

        return Inertia::render('Search/Search', [
            'filters' => $filters,
            'listDepartments' => CategoryFilterResource::collection($listDepartments),
            'listCategories' => CategoryFilterResource::collection($listCategories),
            'listColors' => ColorResource::collection($listColors),
            'products' => ProductCardResource::collection($products),
            'page' => new PageResource($page),
            'banner' => $banner,
        ]);
    }
}
