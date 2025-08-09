<?php

namespace App\Filament\Resources;

use App\Enums\StockMovementOperationEnum;
use App\Filament\Resources\StockAdjustmentResource\Pages;
use App\Filament\Resources\StockAdjustmentResource\RelationManagers;
use App\Helpers\RoleHelper;
use App\Models\Product;
use App\Models\Size;
use App\Models\Sku;
use App\Models\StockAdjustment;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StockAdjustmentResource extends Resource
{
    protected static ?string $model = StockAdjustment::class;

    public static ?string $label = 'Stock';
    protected static ?string $pluralModelLabel = 'Stock';
    protected static ?string $navigationGroup = 'Inventory';
    protected static ?string $navigationIcon = 'heroicon-o-adjustments-vertical';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(4)
            ->schema([
                Select::make('product_id')
                    ->getSearchResultsUsing(function (string $search): array {
                        return Product::select('id', 'name', 'ref', 'color_id')
                            ->with('color:id,name')
                            ->orWhere('name', 'like', "%{$search}%")
                            ->orWhere('ref', 'like', "%{$search}%")
                            ->variant()
                            ->limit(20)
                            ->get()
                            ->mapWithKeys(function ($product) {
                                return [$product->id => "{$product->ref} {$product->name}"];
                            })->toArray();
                    })
                    ->getOptionLabelUsing(fn($value): ?string => Sku::find($value)?->product->name)
                    ->searchable()
                    ->columnSpan(3)
                    ->live()
                    ->afterStateUpdated(function (Set $set, Get $get, $state, $context) {
                        self::changeProductSize($get, $set);
                    })
                    ->label('Product'),

                Forms\Components\TextInput::make('quantity')
                    ->label('Quantity')
                    ->minValue(1)
                    ->maxValue(function ($get) {
                        if ($get('type') == 'subtraction')
                            return $get('current_stock');
                    })
                    ->placeholder(0)
                    ->required()
                    ->live(debounce: 400)
                    ->afterStateUpdated(function (Set $set, Get $get,) {
                        self::changeFormQuantity($get, $set);
                    })
                    ->numeric(),
                Forms\Components\Select::make('type')
                    ->options(StockMovementOperationEnum::class)
                    ->live()
                    ->afterStateUpdated(function (Set $set, Get $get,) {
                        self::changeFormQuantity($get, $set);
                    })
                    ->default('addition')
                    ->label('Type')
                    ->required(),

                Forms\Components\TextInput::make('current_stock')
                    ->label('Current Stock')
                    ->placeholder(0)
                    ->disabled(),

                Forms\Components\TextInput::make('final_stock')
                    ->label('New Stock')
                    ->disabled()
                    ->placeholder(0)
                    ->dehydrated(),

                Forms\Components\Textarea::make('note')
                    ->required()
                    ->placeholder('Adjustment reason')
                    ->columnSpanFull(),

            ]);
    }
    public static function changeProductSize($get, $set)
    {
        if ($get('product_id')) {
            $sku = Sku::where('product_id', $get('product_id'))->first();
            $set('current_stock', $sku ? $sku->stock : 0);
            self::changeFormQuantity($get, $set);
        } else {
            $set('current_stock', null);
        }
        $set('quantity', null);
        $set('final_stock', null);
    }

    public static function changeFormQuantity($get, $set)
    {
        if ($get('quantity') && $get('type')) {
            $final_stock = match ($get('type')) {
                'addition' => $get('current_stock') + $get('quantity'),
                'subtraction' => $get('current_stock') - $get('quantity'),
            };
            $set('final_stock', $final_stock);
        } else {
            // $set('current_stock', null);
            $set('quantity', null);
            $set('final_stock', null);
        }
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                return $query->with('sku.product:id,name,slug,thumb,ref,color_id', 'sku.product.color:id,name');
            })
            ->columns([
                Tables\Columns\ImageColumn::make('sku.product.thumb')
                    ->size(40)->circular()->label('Thumbnail'),
                Tables\Columns\TextColumn::make('sku.product.name')
                    ->wrap()
                    ->url(fn($record) => route('product', [$record->sku->product->slug, $record->sku->product->ref]))
                    ->openUrlInNewTab()
                    ->label('Name')
                    ->formatStateUsing(fn(StockAdjustment $record): string => "{$record->sku->product->ref} {$record->sku->product->name}"),

                Tables\Columns\TextColumn::make('quantity')
                    ->numeric()
                    ->label('Quantity'),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->label('Type'),
                Tables\Columns\TextColumn::make('sku.stock')
                    ->numeric()
                    ->label('Stock'),

                ...DepartmentResource::dateCreatedTable()
            ])
            ->filters([
                SelectFilter::make('sku_id')
                    ->label('Product')
                    ->getSearchResultsUsing(function (string $search): array {
                        return Sku::withWhereHas('product', function ($query) use ($search) {
                            $query
                                ->select('id', 'name', 'ref')->variant()
                                ->where('name', 'like', "%{$search}%")
                                ->orWhere('ref', 'like', "%{$search}%");
                        })
                            ->limit(20)
                            ->get()
                            ->mapWithKeys(function ($sku) {
                                return [$sku->id => "{$sku->product->ref} {$sku->product->name}"];
                            })->toArray();
                    })
                    ->getOptionLabelUsing(fn($value): ?string => Sku::find($value)?->product->name)
                    ->columnSpan(3)
                    ->searchable()
            ], layout: FiltersLayout::AboveContent)
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStockAdjustments::route('/'),
            'create' => Pages\CreateStockAdjustment::route('/create'),
            // 'edit' => Pages\EditStockAdjustment::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return RoleHelper::admin() || RoleHelper::manager() || RoleHelper::seller();
    }
}
