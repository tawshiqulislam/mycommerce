<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Helpers\RoleHelper;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    public static ?string $label = 'User';
    protected static ?string $pluralModelLabel = 'Users';
    protected static ?string $navigationGroup = 'Clients';
    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()->label('Name'),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required(),
                Forms\Components\TextInput::make('phone')->tel()->label('Phone'),
                Forms\Components\TextInput::make('country')->label('Country'),
                Forms\Components\TextInput::make('city')->label('City'),
                Forms\Components\Select::make('role_id')
                    ->label('Role')
                    ->options(
                        DB::table('roles')->pluck('name', 'id')
                    )
                    ->required(),
                Forms\Components\TextInput::make('address')
                    ->columnSpanFull()->label('Address'),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->description(fn($record) => $record->getRoleNames()->first())
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->description(fn($record) => $record->email)
                    ->searchable(),
                Tables\Columns\TextColumn::make('orders_count')
                    ->counts('orders')->label('Orders'),
                Tables\Columns\ToggleColumn::make('verified')
                    ->label('Verified'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('view-orders')
                    ->url(fn(User $record): string => UserResource::getUrl('view-orders', ['record' => $record->id]))
                    ->label('View')->color('gray'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'view-orders' => Pages\ViewUserOrders::route('/{record}/orders'),
        ];
    }

    public static function canViewAny(): bool
    {
        return RoleHelper::admin();
    }
}
