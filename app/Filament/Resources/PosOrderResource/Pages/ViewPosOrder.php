<?php

namespace App\Filament\Resources\PosOrderResource\Pages;

use App\Enums\OrderStatusEnum;
use App\Enums\PaymentMethodEnum;
use App\Filament\Resources\PosOrderResource;
use App\Models\PosOrder;
use Filament\Actions;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Number;
use Livewire\Attributes\On;
use Filament\Infolists\Components\Actions as ComponentsActions;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Support\Enums\Alignment;

class ViewPosOrder extends ViewRecord
{
    protected static string $resource = PosOrderResource::class;

    public function getTitle(): string
    {
        return "Order {$this->record->buyer_phone} - " . Number::currency($this->record->total);
    }

    #[On('refreshViewOrder')]
    public function refresh(): void {}

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Grid::make(4)
                    ->schema([
                        Section::make([
                            TextEntry::make('seller_phone')->label('Seller phone'),
                            TextEntry::make('seller_name')->label('Seller name'),
                            TextEntry::make('buyer_phone')->label('Buyer Phone'),
                            ViewEntry::make('pos_order_products')->columnSpanFull()->view('filament.infolists.pos-view')
                        ])->columnSpan(2)->columns(3),
                    ]),
            ]);
    }
}
