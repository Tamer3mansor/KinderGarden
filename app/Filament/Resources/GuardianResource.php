<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GuardianResource\Pages;
use App\Models\Guardian;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GuardianResource extends Resource
{
    protected static ?string $model = Guardian::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getModelLabel(): string
    {
        return __('guardians.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('guardians.plural_label');
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('view_any_guardian') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label(__('guardians.name'))
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('phone')
                ->label(__('guardians.phone'))
                ->tel()
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('email')
                ->label(__('guardians.email'))
                ->email()
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('national_id')
                ->label(__('guardians.national_id'))
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('relation_to_child')
                ->label(__('guardians.relation_to_child'))
                ->required()
                ->maxLength(255),

            Forms\Components\Select::make('students')
                ->label(__('guardians.students'))
                ->relationship('students', 'name')
                ->multiple()
                ->preload(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('guardians.name'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label(__('guardians.phone'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label(__('guardians.email'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('national_id')
                    ->label(__('guardians.national_id'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('relation_to_child')
                    ->label(__('guardians.relation_to_child')),

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
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListGuardians::route('/'),
            'create' => Pages\CreateGuardian::route('/create'),
            'edit' => Pages\EditGuardian::route('/{record}/edit'),
        ];
    }
}
