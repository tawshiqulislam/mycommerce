<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PosOrderResource\Pages;
use App\Filament\Resources\PosOrderResource\RelationManagers;
use App\Models\Page;
use App\Models\PosOrder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use App\Filament\Resources\PosOrderResource\Pages\ViewPosOrder;
use App\Helpers\RoleHelper;

class PosOrderResource extends Resource
{
    protected static ?string $model = PosOrder::class;

    public static ?string $label = 'POS Order';

    protected static ?string $pluralModelLabel = 'POS Orders';

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

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
                TextColumn::make('seller_name')->searchable(),
                TextColumn::make('seller_phone')->searchable(),
                TextColumn::make('buyer_phone')->searchable(),
                TextColumn::make('total')->label('Total')->numeric()->money('BDT'),
                TextColumn::make('created_at')->label('Date')
                    ->sortable()->dateTime()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                self::filtersDate()

            ])
            ->actions([
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
            'index' => Pages\ListPosOrders::route('/'),
            'view' => ViewPosOrder::route('/{record}'),
        ];
    }
}
