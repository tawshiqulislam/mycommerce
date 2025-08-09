<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use App\Models\OrderProduct;
// use Dashboard;
use App\Filament\Pages\Dashboard;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Number;

class StatsOverview extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static bool $isLazy = false;
    protected static ?int $sort = 0;
    protected function getStats(): array
    {

        $filterMonth = Dashboard::filterDateSelected($this->filters['select_month']);

        $orders = Order::select('id', 'status', 'created_at', 'total')
            ->where('status', OrderStatusEnum::SUCCESSFUL)
            ->when($filterMonth, fn(Builder $query) => $query->whereDate('created_at', '>=', $filterMonth))
            ->orderBy('created_at', 'desc')->get();

        $ordersPerDays = $orders->groupBy(function ($order) {
            return (int) $order->created_at->format('d');
        })->map(function ($item) {
            return $item->count();
        });

        $productBestSeller = OrderProduct::select(
            DB::raw('MAX(id) as id'),
            'name',
            'price',
            'product_id',
            DB::raw('MAX(order_id) as order_id'),
            DB::raw('COUNT(*) as products_count')
        )
            ->groupBy('product_id', 'name', 'price')
            ->orderBy('products_count', 'desc')
            ->when($filterMonth, fn(Builder $query) => $query->whereDate('created_at', '>=', $filterMonth))
            ->first();

        if ($productBestSeller) {
            $statProductBestSeller = Stat::make('Best seller', $productBestSeller->products_count . ' sales')
                ->description($productBestSeller->name . ' ' . Number::currency($productBestSeller->price));
        } else {
            $statProductBestSeller = Stat::make('Best seller', '0 sales')
                ->description('No hay suficientes datos');
        }

        return [
            Stat::make('Sales', $orders->count() . ' sales')
                ->description(Number::currency($orders->sum('total')))
                ->chart($ordersPerDays->toArray())->color('success'),

            $statProductBestSeller,

            Stat::make('Average sale', Number::currency($orders->avg('total') ?: 0, locale: 'en'))
        ];
    }
}