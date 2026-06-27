<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClassroomResource\Pages;
use App\Filament\Resources\ClassroomResource\RelationManagers;
use App\Models\Classroom;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;


class ClassroomResource extends Resource
{
    protected static ?string $model = Classroom::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
public static function getModelLabel(): string
{
    return __('classrooms.model_label');
}

// اسم الـ Resource في الـ Sidebar — "الفصول" أو "Classrooms"
public static function getPluralModelLabel(): string
{
    return __('classrooms.plural_label');
}
    public static function form(Form $form): Form
    {
        // name level_stage max_capacity
        
        return $form
            ->schema([

TextInput::make('name')->label(__('classrooms.name'))
->required(),
 
TextInput::make('level_stage')->hint('G1,G2,G3')->required()->label(__('classrooms.level_stage')),
TextInput::make('max_capacity')->numeric()->required()->label(__('classrooms.capacity'))
 

    
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
               TextColumn::make('name')->label(__('classrooms.name')),
  
TextColumn::make('level_stage')->label(__('classrooms.level_stage')),
TextColumn::make('max_capacity')->label(__('classrooms.capacity'))
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListClassrooms::route('/'),
            'create' => Pages\CreateClassroom::route('/create'),
            'edit' => Pages\EditClassroom::route('/{record}/edit'),
        ];
    }
}
