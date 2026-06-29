<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FeePlanResource\Pages;
use App\Models\FeePlan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FeePlanResource extends Resource
{
    protected static ?string $model = FeePlan::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function getModelLabel(): string
    {
        return __('fee_plans.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('fee_plans.plural_label');
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('view_any_fee_plan') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label(__('fee_plans.name'))
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('amount')
                ->label(__('fee_plans.amount'))
                ->numeric()
                ->required()
                ->prefix('EGP'),

            Forms\Components\TextInput::make('duration_months')
                ->label(__('fee_plans.duration_months'))
                ->numeric()
                ->required(),

            Forms\Components\TextInput::make('discount_percentage')
                ->label(__('fee_plans.discount_percentage'))
                ->numeric()
                ->suffix('%')
                ->default(0),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label(__('fee_plans.name'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('amount')
                    ->label(__('fee_plans.amount'))
                    ->money('EGP')
                    ->sortable(),

                Tables\Columns\TextColumn::make('duration_months')
                    ->label(__('fee_plans.duration_months'))
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('discount_percentage')
                    ->label(__('fee_plans.discount_percentage'))
                    ->numeric(decimalPlaces: 2)
                    ->suffix('%')
                    ->sortable(),

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
            'index' => Pages\ListFeePlans::route('/'),
            'create' => Pages\CreateFeePlan::route('/create'),
            'edit' => Pages\EditFeePlan::route('/{record}/edit'),
        ];
    }
}
