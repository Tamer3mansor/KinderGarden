<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use App\Filament\Resources\RoleResource\RelationManagers;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Spatie\Permission\Models\Role;


class RoleResource extends Resource
{
    protected static ?string $model = Role::class;
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static ?string $slug = 'roles';

    public static function getNavigationGroup(): ?string
    {
        return __('roles.navigation_group');
    }

    public static function getModelLabel(): string
    {
        return __('roles.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('roles.plural_label');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')
                ->label(__('roles.name'))
                ->required()
                ->unique(ignoreRecord: true),

            Hidden::make('guard_name')
                ->default('admin'),

            Select::make('permissions')
                ->label(__('roles.permissions'))
                ->relationship('permissions', 'name')
                ->multiple()
                ->preload(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label(__('roles.name'))->searchable(),
                TextColumn::make('guard_name')->label('Guard')->badge(),
                TextColumn::make('permissions_count')
                    ->label(__('roles.permissions_count'))
                    ->counts('permissions'),
            ])
            ->filters([
                SelectFilter::make('guard_name')
                    ->label('Guard')
                    ->options(fn () => array_combine(
                        array_keys(config('auth.guards')),
                        array_keys(config('auth.guards'))
                    )),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
