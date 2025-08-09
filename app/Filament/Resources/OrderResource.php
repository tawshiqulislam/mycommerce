<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\Pages\ViewOrder;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Helpers\RoleHelper;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    public static ?string $label = 'Order';

    protected static ?string $pluralModelLabel = 'Orders';

    public static function getNavigationBadge(): ?string
    {
        return 'today ' . static::getModel()::whereDate('created_at', now()->setTime(0, 0))->count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')->label('Code')->searchable(),
                TextColumn::make('data.user.name')->label('Client')->wrap()->searchable(),
                TextColumn::make('order_products_count')->label('Products')->counts('order_products'),
                TextColumn::make('shipping')->label('Shipping')->numeric(),
                TextColumn::make('total')->label('Total')->numeric(),
                TextColumn::make('status')->badge(),
                TextColumn::make('payment.method')->label('Payment type')->badge(),
                TextColumn::make('created_at')->label('Date')
                    ->sortable()->dateTime()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                self::filtersDate()

            ])
            ->actions([
                // Tables\Actions\EditAction::make()->icon(null),
                Tables\Actions\ViewAction::make()->icon(null)->label('View order'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function filtersDate()
    {
        return Filter::make('created_at')
            ->form([
                DatePicker::make('created_from')->label('From')->native(false),
                DatePicker::make('created_until')->label('To')->native(false),
            ])->query(function (Builder $query, array $data): Builder {
                return $query
                    ->when(
                        $data['created_from'],
                        fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                    )
                    ->when(
                        $data['created_until'],
                        fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                    );
            });
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
            'view' => ViewOrder::route('/{record}'),
        ];
    }

    public static function canViewAny(): bool
    {
        return RoleHelper::admin() || RoleHelper::manager() || RoleHelper::support();
    }
}
