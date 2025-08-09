<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use App\Filament\Resources\OrderResource;
use App\Models\OrderProduct;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'order_products';

    protected static ?string $title = 'Orders';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('order.code')->label('Code'),
                // Tables\Columns\TextColumn::make('name')->wrap()->label('Name'),
                // Tables\Columns\TextColumn::make('color')->label('Brand'),
                // Tables\Columns\TextColumn::make('size')->label('Tamaño'),
                Tables\Columns\TextColumn::make('price')->money('BDT')->label('Price'),
                Tables\Columns\TextColumn::make('quantity')->label('Quantity'),
                Tables\Columns\TextColumn::make('total')->money('BDT')->label('Total'),
                Tables\Columns\TextColumn::make('order.status')->badge()->label('Status'),
                Tables\Columns\TextColumn::make('created_at')->label('Date')->sortable()->dateTime()->toggleable(isToggledHiddenByDefault: true)
            ])
            ->filters([
                // SelectFilter::make('size')
                //     ->options(function ($livewire) {
                //         dd($livewire->ownerRecord);
                //     }),
                OrderResource::filtersDate()
            ])
            ->headerActions([])
            ->actions([
                Action::make('view-order-detail')
                    ->url(fn(OrderProduct $record): string => OrderResource::getUrl('view', ['record' => $record->order_id]))
                    ->label('View order')->color('info'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
