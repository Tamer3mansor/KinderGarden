<?php

namespace App\Filament\Resources;

use App\Enums\AdminType;
use App\Filament\Resources\AdminResource\Pages;
use App\Models\Admin;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class AdminResource extends Resource
{
    protected static ?string $model = Admin::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    public static function getNavigationGroup(): ?string
    {
        return __('roles.navigation_group');
    }

    public static function getModelLabel(): string
    {
        return __('admins.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admins.plural_label');
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('view_any_admin') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema(function () {
            $schema = [
                TextInput::make('name')
                    ->label(__('admins.name'))
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->label(__('admins.email'))
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                TextInput::make('password')
                    ->label(__('admins.password'))
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->maxLength(255),

                Select::make('type')
                    ->label(__('admins.type'))
                    ->options(AdminType::class)
                    ->required()
                    ->live(),

                TextInput::make('phone')
                    ->label(__('admins.phone'))
                    ->tel()
                    ->visible(fn ($get) => $get('type') === 'teacher'),

                TextInput::make('specialization')
                    ->label(__('admins.specialization'))
                    ->visible(fn ($get) => $get('type') === 'teacher'),

                DatePicker::make('hire_date')
                    ->label(__('admins.hire_date'))
                    ->visible(fn ($get) => $get('type') === 'teacher'),

                TextInput::make('salary')
                    ->label(__('admins.salary'))
                    ->numeric()
                    ->prefix('EGP')
                    ->visible(fn ($get) => $get('type') === 'teacher'),

                Select::make('status')
                    ->label(__('admins.status'))
                    ->options([
                        'active'   => __('admins.status_active'),
                        'inactive' => __('admins.status_inactive'),
                    ])
                    ->visible(fn ($get) => $get('type') === 'teacher'),

                DatePicker::make('contract_end_date')
                    ->label(__('admins.contract_end_date'))
                    ->visible(fn ($get) => $get('type') === 'teacher'),

                Select::make('classrooms')
                    ->label(__('admins.classrooms'))
                    ->relationship('classrooms', 'name')
                    ->multiple()
                    ->preload()
                    ->visible(fn ($get) => $get('type') === 'teacher'),

                Select::make('roles')
                    ->label(__('admins.roles'))
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload(),
            ];

            return $schema;
        });
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('admins.name'))
                    ->searchable(),

                TextColumn::make('email')
                    ->label(__('admins.email'))
                    ->searchable(),

                TextColumn::make('type')
                    ->label(__('admins.type'))
                    ->badge()
                    ->color(fn (string $state) => AdminType::tryFrom($state)?->color() ?? 'gray'),

                TextColumn::make('roles.name')
                    ->label(__('admins.roles'))
                    ->badge()
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label(__('admins.type'))
                    ->options(AdminType::class),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAdmins::route('/'),
            'create' => Pages\CreateAdmin::route('/create'),
            'edit' => Pages\EditAdmin::route('/{record}/edit'),
        ];
    }
}
