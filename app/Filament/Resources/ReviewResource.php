<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Filament\Resources\ReviewResource\RelationManagers;
use App\Helpers\RoleHelper;
use App\Models\Review;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Support\Facades\DB;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    public static ?string $label = 'Review';
    protected static ?string $pluralModelLabel = 'Reviews';
    protected static ?string $navigationGroup = 'Clients';
    protected static ?string $navigationIcon = 'heroicon-o-star';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\TextInput::make('company')->label('Affiliation'),
                Forms\Components\TextInput::make('rating')
                    ->numeric()
                    ->required()
                    ->minValue(1)
                    ->maxValue(5),
                Forms\Components\Toggle::make('featured'),
                Forms\Components\Textarea::make('review')
                    ->required()
                    ->rows(4),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable(),
                TextColumn::make('company')->label('Affiliation'),
                TextColumn::make('rating')->sortable(),
                Tables\Columns\ToggleColumn::make('featured'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                BulkAction::make('setFeatured')
                    ->label('Feature all')
                    ->color('primary')
                    ->icon('heroicon-o-arrow-uturn-up')
                    ->link()
                    ->action(function ($records) {
                        $recordIds = $records->pluck('id')->toArray();
                        DB::table((new Review)->getTable())
                            ->whereIn('id', $recordIds)
                            ->update(['featured' => true]);
                    })
                    ->requiresConfirmation(),

                BulkAction::make('unsetFeatured')
                    ->color('danger')
                    ->link()
                    ->icon('heroicon-o-x-circle')
                    ->label('Feature none')
                    ->action(function ($records) {
                        $recordIds = $records->pluck('id')->toArray();
                        DB::table((new Review)->getTable())
                            ->whereIn('id', $recordIds)
                            ->update(['featured' => false]);
                    })
                    ->requiresConfirmation(),
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
            'index' => Pages\ListReviews::route('/'),
            'create' => Pages\CreateReview::route('/create'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return RoleHelper::admin() || RoleHelper::manager() || RoleHelper::support();
    }
}
