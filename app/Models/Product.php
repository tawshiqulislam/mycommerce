<?php

namespace App\Models;

use App\Enums\OrderStatusEnum;

use Illuminate\Database\Eloquent\Builder;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Log;

class Product extends Model
{

    use HasFactory;

    protected $casts = [
        'price' => 'float',
    ];

    public function metaTag()
    {
        return $this->morphOne(MetaTag::class, 'model');
    }
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
    public function sku()
    {
        return $this->hasOne(Sku::class);
    }

    public function skus()
    {
        return $this->hasMany(Sku::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'model');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function specifications(): HasMany
    {
        return $this->hasMany(Specification::class);
    }
    public function specificationsGroup(): HasMany
    {
        return $this->hasMany(Specification::class);
    }

    public function shopping_cart(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('quantity', 'total_price_quantity');
    }

    public function order_products(): HasMany
    {
        return $this->hasMany(OrderProduct::class)->has('order');
    }

    public function orders(): HasManyThrough
    {
        return $this->hasManyThrough(
            Order::class,
            OrderProduct::class,
            'product_id',
            'id',
            'id',
            'order_id'
        );
    }

    public function scopeVariant(Builder $query): void
    {
        $query->whereNotNull('id');
    }

    public function scopeInOffer(Builder $query): void
    {
        $query->where('offer', '!=', 0);
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('active', 1);
    }

    public function scopeInStock(Builder $query): void
    {
        $query->whereRelation('skus', 'stock', '>', 0);
    }

    public function scopeActiveInStock($query): void
    {
        $query->active()->inStock();
    }

    public function scopeCard(Builder $query): void
    {
        $query->select(
            'products.id',
            'slug',
            'name',
            'thumb',
            'ref',
            'offer',
            'price',
            'old_price',
            'department_id',
            'color_id',
            'category_id',
            'updated_at'
        )->with([
            'product' => function ($query) {
                $query->select('id')->with('variants.color');
            }
        ]);
    }

    public function scopeBestSeller(Builder $query): void
    {
        $query->withCount([
            'orders' => function ($query) {
                $query->where('status', OrderStatusEnum::SUCCESSFUL);
            }
        ])->orderBy('orders_count', 'desc');
    }

    public function scopeWithFilters($query, $filters)
    {
        return $query
            ->activeInStock()
            ->when($filters['q'], function (Builder $query) use ($filters) {
                $query->where(function ($query) use ($filters) {
                    $query->orWhere('name', 'like', '%' . $filters['q'] . '%');
                    $query->orWhere('slug', 'like', '%' . $filters['q'] . '%');
                    $query->orWhere('entry', 'like', '%' . $filters['q'] . '%');
                });
            })
            ->when($filters['departments'], function (Builder $query) use ($filters) {
                $query->whereIn('department_id', $filters['departments']);
            })->when($filters['categories'], function (Builder $query) use ($filters) {
                $query->whereIn('category_id', $filters['categories']);
            })->when($filters['colors'], function (Builder $query) use ($filters) {
                $query->whereIn('color_id', $filters['colors']);
            })->when($filters['price_min'], function (Builder $query) use ($filters) {
                $query->where('price', '>=', $filters['price_min']);
            })->when($filters['price_max'], function (Builder $query) use ($filters) {
                $query->where('price', '<=', $filters['price_max']);
            })->when($filters['offer'], function (Builder $query) use ($filters) {
                $query->where('offer', '>=', $filters['offer']);
            })
            ->when($filters['sortBy'], function (Builder $query) use ($filters) {
                $sorBy = $filters['sortBy'] == 'price_desc' ? 'desc' : 'asc';
                $query->orderBy('price', $sorBy);
            }, function (Builder $query) {
                $query->orderBy('products.created_at', 'desc');
            })
        ;
    }
}
