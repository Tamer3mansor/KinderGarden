<?php

namespace App\Filament\Resources;

use App\Enums\TeacherStatus;
use App\Filament\Resources\TeacherResource\Pages;
use App\Filament\Resources\TeacherResource\RelationManagers;
use App\Models\Teacher;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TeacherResource extends Resource
{
    protected static ?string $model = Teacher::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
public static function getModelLabel(): string
{
    return __('teachers.model_label');
}

 public static function getPluralModelLabel(): string
{
    return __('teachers.plural_label');
}
 
public static function form(Form $form): Form
{
    return $form->schema([
        TextInput::make('name')
            ->label(__('teachers.name'))
            ->required(),

        TextInput::make('phone')
            ->label(__('teachers.phone'))
            ->tel()
            ->required(),

        TextInput::make('email')
            ->label(__('teachers.email'))
            ->email()
            ->required(),

        TextInput::make('specialization')
            ->label(__('teachers.specialization'))
            ->required(),

        DatePicker::make('hire_date')
            ->label(__('teachers.hire_date'))
            ->required(),

        TextInput::make('salary')
            ->label(__('teachers.salary'))
            ->numeric()
            ->prefix('EGP')
            ->required(),

        Select::make('status')
            ->label(__('teachers.status'))
            ->options(TeacherStatus::class)
            ->required(),

        Select::make('classrooms')
            ->label(__('teachers.classrooms'))
            ->relationship('classrooms', 'name')
            ->multiple()
            ->preload(),
    ]);
}
    

   public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('name')
                ->label(__('teachers.name'))
                ->searchable(),

            TextColumn::make('phone')
                ->label(__('teachers.phone')),

            TextColumn::make('email')
                ->label(__('teachers.email'))
                ->searchable(),

            TextColumn::make('specialization')
                ->label(__('teachers.specialization')),

            TextColumn::make('hire_date')
                ->label(__('teachers.hire_date'))
                ->date(),

            TextColumn::make('salary')
                ->label(__('teachers.salary'))
                ->money('EGP'),

            TextColumn::make('status')
                ->label(__('teachers.status'))
                ->badge()
         ])
        ->filters([
    SelectFilter::make('classrooms')
        ->label(__('teachers.classrooms'))
        ->relationship('classrooms', 'name')
        ->multiple()
        ->preload(),

    SelectFilter::make('status')
        ->label(__('teachers.status'))
        ->options(TeacherStatus::class),
])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListTeachers::route('/'),
            'create' => Pages\CreateTeacher::route('/create'),
            'edit' => Pages\EditTeacher::route('/{record}/edit'),
        ];
    }
}
