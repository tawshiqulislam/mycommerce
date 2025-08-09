<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShippingResource\Pages;
use App\Helpers\RoleHelper;
use App\Models\Shipping;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;

class ShippingResource extends Resource
{
    protected static ?string $model = Shipping::class;
    protected static ?int $navigationSort = 3;
    public static ?string $label = 'Shipping';
    protected static ?string $pluralModelLabel = 'Shipping';
    protected static ?string $navigationIcon = 'heroicon-o-truck';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(self::formShipping())->columns(1);
    }

    public static function formShipping(): array
    {
        return [
            Forms\Components\TextInput::make('area')
                ->required(),
            Forms\Components\TextInput::make('cost')
                ->required()
                ->numeric()
                ->placeholder(0)
                ->prefix('৳'),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('area')->label('Area')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cost')->label('Cost')
                    ->money('BDT')
                    ->sortable(),
                ...DepartmentResource::dateCreatedTable()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->modalWidth(MaxWidth::Medium),
                Tables\Actions\DeleteAction::make()
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
            'index' => Pages\ListShippings::route('/'),
        ];
    }

    public static function canViewAny(): bool
    {
        return RoleHelper::admin() || RoleHelper::manager();
    }
}
