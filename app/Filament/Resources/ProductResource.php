<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Helpers\RoleHelper;
use App\Models\Color;
use App\Models\Product;
use App\Services\ProductService;
use Filament\Actions\Contracts\ReplicatesRecords;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ReplicateAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    public static ?string $label = 'Product';
    protected static ?string $pluralModelLabel = 'Products';
    protected static ?string $navigationGroup = 'Inventory';
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::variant()->count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->live(debounce: 500)
                    ->afterStateUpdated(function (Set $set, $state, $context) {
                        if ($context === 'edit') {
                            return;
                        }
                        $set('slug', Str::slug($state));
                    })
                    ->maxLength(255)
                    ->required()
                    ->columnSpan(4)
                    ->label('Name'),

                Forms\Components\TextInput::make('ref')
                    ->maxLength(255)
                    ->columnSpan(2)
                    ->disabled()
                    ->helperText('This will be auto-generated')
                    ->label('Reference'),

                Forms\Components\TextInput::make('slug')
                    ->maxLength(255)
                    ->required()
                    ->prefix(url('/product') . '/')
                    ->suffix(fn(Get $get) => "/ref/{$get('ref')}")
                    ->columnSpan(4),

                Forms\Components\Select::make('color_id')
                    ->relationship('color', 'name')
                    ->required()
                    ->columnSpan(2)
                    ->preload()
                    ->suffixIcon('heroicon-m-swatch')
                    ->suffixIconColor('info')
                    ->createOptionForm(ColorResource::formColor())
                    ->label('Unit'),

                Forms\Components\Select::make('department_id')
                    ->relationship('department', 'name')
                    ->required()
                    ->columnSpan(2)
                    ->label('Department'),

                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name')
                    ->options(function () {
                        return \App\Models\Category::where('type', 'product')->pluck('name', 'id');
                    })
                    ->required()
                    ->columnSpan(2)
                    ->label('Category'),

                Forms\Components\TextInput::make('max_quantity')
                    ->required()
                    ->numeric()
                    ->label('Max Quantity')
                    ->columnSpan(2)
                    ->default(1),

                Forms\Components\Toggle::make('featured')
                    ->required()
                    ->columnSpan(2)
                    ->label('Referral'),

                Forms\Components\Toggle::make('active')
                    ->required()
                    ->columnSpan(2)
                    ->label('Active'),

                Forms\Components\Textarea::make('entry')
                    ->required()
                    ->columnSpanFull()
                    ->label('Entry'),

                Forms\Components\RichEditor::make('description')
                    ->disableToolbarButtons(['attachFiles'])
                    ->columnSpanFull()
                    ->label('Description'),

                Forms\Components\FileUpload::make('thumb')->directory('/img/products/thumb')
                    ->columnSpan(3)
                    ->required()
                    ->label('Thumbnail'),

                Forms\Components\FileUpload::make('img')->directory('/img/products')
                    ->columnSpan(3)
                    ->required()
                    ->label('Image'),

                self::formPrice(),
                Grid::make(2),
            ])
            ->columns(6);
    }

    public static function formPrice()
    {
        return Forms\Components\Fieldset::make('Price')
            ->columns(4)
            ->schema([
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->placeholder(0)
                    ->prefix('৳')
                    ->live(debounce: 500)
                    ->afterStateUpdated(function (Set $set, Get $get) {
                        self::changePrice($set, $get);
                    })
                    ->label('Price'),

                Forms\Components\TextInput::make('old_price')
                    ->numeric()
                    ->live(debounce: 500)
                    ->afterStateUpdated(function (Set $set, Get $get) {
                        self::changePrice($set, $get);
                    })
                    ->prefix('৳')
                    ->placeholder(0)
                    ->minValue(fn(Get $get, $state) => $state ? $get('price') : 0)
                    ->label('Base Price'),

                Forms\Components\TextInput::make('offer')
                    ->numeric()
                    ->maxValue(99)
                    ->minValue(0)
                    ->placeholder(0)
                    ->suffix('%')
                    // ->disabled()
                    ->label('Discount'),
            ]);
    }

    public static function changePrice($set, $get)
    {
        if (!$get('old_price')) {
            return;
        }
        if ($get('price') > $get('old_price')) {
            Notification::make()
                ->title('Price should be less than/equal to base price')
                ->danger()
                ->send();
            return;
        }
        $offer = (($get('old_price') - $get('price')) / $get('old_price')) * 100;
        $set('offer', round($offer));
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                return $query->variant()->withSum('skus', 'stock')->with('color');
            })

            ->columns([
                Tables\Columns\ImageColumn::make('thumb')
                    ->width(40)->label('Thumbnail'),

                Tables\Columns\TextColumn::make('name')
                    ->url(fn($record) => route('product', [$record->slug, $record->ref]))
                    ->openUrlInNewTab()
                    ->wrap(true)
                    ->description(fn($record): string => "ref " . $record->ref)
                    ->searchable()
                    ->label('Name'),

                Tables\Columns\TextColumn::make('color.name')
                    ->searchable()
                    ->label('Unit'),

                Tables\Columns\TextColumn::make('price')
                    ->money('BDT')
                    ->sortable()
                    ->label('Price'),

                Tables\Columns\TextColumn::make(name: 'skus_sum_stock')
                    ->numeric()
                    ->sortable()
                    ->label('Stock'),

                Tables\Columns\ToggleColumn::make('featured')
                    ->label('Referral'),

                Tables\Columns\ToggleColumn::make('active')
                    ->label('Active'),

                ...DepartmentResource::dateCreatedTable()
            ])
            ->filters([
                SelectFilter::make('category')
                    ->relationship('category', 'name', modifyQueryUsing: fn(Builder $query) => $query->where('type', 'product'),)
                    ->preload()
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->icon('heroicon-o-pencil-square')
                        ->color('info'),
                    Tables\Actions\DeleteAction::make()
                        ->before(function ($record) {
                            foreach ($record->skus as $sku) {
                                $sku->stock_adjustments()->delete();
                            }
                            $record->skus()->delete();
                        })
                        ->icon('heroicon-o-trash'),

                    Action::make('product-relations')
                        ->url(fn(Product $record): string => ProductResource::getUrl('product-relations', ['record' => $record->id]))
                        ->label('Related Data')->color('gray')->icon('heroicon-o-squares-2x2'),
                ])
                    ->link()
                    ->icon('heroicon-o-chevron-down')
                    ->label('Options'),

                Action::make('product-stock')
                    ->url(fn(Product $record): string => ProductResource::getUrl('product-stock', ['record' => $record->id]))
                    ->label('Stock')->color('gray'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
            'product-relations' => Pages\ViewProductRelationship::route('/product/{record}/relations'),
            'product-stock' => Pages\ViewProductStock::route('/{record}/skus'),
        ];
    }

    public static function canViewAny(): bool
    {
        return RoleHelper::admin() || RoleHelper::manager() || RoleHelper::seller();
    }
}
