<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ColorResource\Pages;
use App\Filament\Resources\ColorResource\RelationManagers;
use App\Helpers\RoleHelper;
use App\Models\Color;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ColorResource extends Resource
{
    protected static ?string $model = Color::class;
    protected static ?int $navigationSort = 3;
    public static ?string $label = 'Unit';
    protected static ?string $pluralModelLabel = 'Units';
    protected static ?string $navigationGroup = 'Inventory';
    protected static ?string $navigationIcon = 'heroicon-o-swatch';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(self::formColor())->columns(1);
    }

    public static function formColor(): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->required(),
            // ->live(debounce: 500)
            // ->afterStateUpdated(function (Set $set, $state, $context) {
            //     if ($context === 'edit') {
            //         return;
            //     }
            // $set('slug', Str::slug($state));
            // })->label('Name'),
            // Forms\Components\Hidden::make('slug')
            //     ->required(),
            // Forms\Components\FileUpload::make('img')->directory('/img/colors')->label('Thumbnail'),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Tables\Columns\ImageColumn::make('img')
                //     ->circular()->size(40)->label('Thumbnail'),
                Tables\Columns\TextColumn::make('name')->label('Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('products_count')->counts('products')->searchable()
                    ->label('Products'),
                ...DepartmentResource::dateCreatedTable()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->modalWidth(MaxWidth::Medium),
                // ->before(function (Color $record) {
                //     Storage::delete($record->img);
                // }),
                Tables\Actions\DeleteAction::make()
                // ->before(function (Color $record) {
                //     Storage::delete($record->img);
                // })
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
            'index' => Pages\ListColors::route('/'),
            // 'create' => Pages\CreateColor::route('/create'),
            // 'edit' => Pages\EditColor::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return RoleHelper::admin() || RoleHelper::manager() || RoleHelper::seller();
    }
}
