<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductRefundResource\Pages;
use App\Models\ProductRefund;
use Filament\Forms;
use App\Models\Order;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;

class ProductRefundResource extends Resource
{
    protected static ?string $model = ProductRefund::class;

    protected static ?string $navigationIcon = 'heroicon-o-document';
    protected static ?string $navigationLabel = 'Refund Requests';
    protected static ?string $navigationGroup = 'Orders';

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductRefunds::route('/'),
            'create' => Pages\CreateProductRefund::route('/create'),
            'edit' => Pages\EditProductRefund::route('/{record}/edit'),
        ];
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('order_code')
                ->label('Order Code')
                ->url(function (ProductRefund $record) {
                    // Get the order using the order_code
                    $order = Order::where('code', $record->order_code)->first();
                    
                    if ($order) {
                        return "/admin/orders/{$order->id}";
                    }
                    
                    return null; // No URL if order not found
                })
                ->openUrlInNewTab(),
                TextColumn::make('price')->label('Price')->sortable(),
                TextColumn::make('point')->label('Points')->sortable(),
                TextColumn::make('quantity')->label('Quantity')->sortable(),
                // Manually handling the status with TextColumn
                TextColumn::make('status')
                    ->label('Status')
                    ->sortable()
                    ->getStateUsing(fn(ProductRefund $record) => $record->status) // Use status directly
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            0 => 'Pending',
                            1 => 'Rejected',
                            2 => 'Approved',
                            default => 'Unknown'
                        };
                    })
            ])
            ->actions([
                ActionGroup::make([ // Group actions into a dropdown
                    Action::make('approve')
                        ->label('Approve')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (ProductRefund $record) {
                            // Update the status to "Approved" (2)
                            $record->update(['status' => 2]);
                        }),
                    Action::make('reject')
                        ->label('Reject')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function (ProductRefund $record) {
                            // Update the status to "Rejected" (1)
                            $record->update(['status' => 1]);
                        }),
                    Action::make('pending')
                        ->label('Pending')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->action(function (ProductRefund $record) {
                            // Update the status to "Pending" (0)
                            $record->update(['status' => 0]);
                        }),
                ])
                ->label('Actions') // Label for the dropdown button
                ->color('primary') // Color of the dropdown button
                ->button(), // Render as a button instead of a link
            ]);
    }
}