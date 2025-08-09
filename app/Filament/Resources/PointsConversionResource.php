<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PointsConversionResource\Pages;
use App\Filament\Resources\PointsConversionResource\RelationManagers;
use App\Helpers\RoleHelper;
use App\Models\PointsConversion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Forms\Components\Grid;

class PointsConversionResource extends Resource
{
    protected static ?string $model = PointsConversion::class;
    public static ?string $label = 'Points & VAT settings';
    protected static ?string $pluralModelLabel = 'Points & VAT';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // in app/Filament/Resources/PointsConversionResource.php

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
                    ->schema([
                        TextInput::make('points')
                            ->required()
                            ->numeric()
                            ->label('Points'),
                        TextInput::make('value')
                            ->required()
                            ->numeric()
                            ->prefix('৳')
                            ->label('Value'),
                        TextInput::make('max_percentage')
                            ->required()
                            ->numeric()
                            ->label('Max % of Order'),
                    ])
                    ->columns(3),
                Grid::make()
                    ->schema([
                        TextInput::make('vat')
                            ->required()
                            ->numeric()
                            ->label('VAT %'),
                        TextInput::make('vat_negation')
                            ->required()
                            ->numeric()
                            ->label('VAT Negation %'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('points')
                    ->label('Points'),
                TextColumn::make('value')
                    ->money('BDT')
                    ->label('Value'),
                TextColumn::make('max_percentage')
                    ->formatStateUsing(fn(string $state): string => $state . '%')
                    ->label('Max % of Order'),
                TextColumn::make('vat')
                    ->formatStateUsing(fn(string $state): string => $state . '%')
                    ->label('VAT %'),
                TextColumn::make('vat_negation')
                    ->formatStateUsing(fn(string $state): string => $state . '%')
                    ->label('VAT Negation %'),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                // DeleteAction::make(),
            ])
            ->bulkActions([
                // DeleteBulkAction::make(),
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
            'index' => Pages\ListPointsConversions::route('/'),
            'create' => Pages\CreatePointsConversion::route('/create'),
            'edit' => Pages\EditPointsConversion::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return RoleHelper::admin() || RoleHelper::manager();
    }
}
