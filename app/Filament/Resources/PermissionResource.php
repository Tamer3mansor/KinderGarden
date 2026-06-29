<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermissionResource\Pages;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Spatie\Permission\Models\Permission;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;
    protected static ?string $navigationIcon = 'heroicon-o-key';
    protected static ?string $slug = 'permissions';

    public static function getNavigationGroup(): ?string
    {
        return __('permissions.navigation_group');
    }

    public static function getModelLabel(): string
    {
        return __('permissions.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('permissions.plural_label');
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('view_any_permission') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')
                ->label(__('permissions.name'))
                ->required()
                ->unique(ignoreRecord: true),

            Hidden::make('guard_name')
                ->default('admin'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label(__('permissions.name'))->searchable(),
                TextColumn::make('guard_name')->label('Guard')->badge(),
                TextColumn::make('roles_count')
                    ->label(__('permissions.roles_count'))
                    ->counts('roles'),
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
            'index' => Pages\ListPermissions::route('/'),
            'create' => Pages\CreatePermission::route('/create'),
            'edit' => Pages\EditPermission::route('/{record}/edit'),
        ];
    }
}
