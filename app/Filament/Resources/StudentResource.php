<?php

namespace App\Filament\Resources;

use App\Enums\StudentGender;
use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getModelLabel(): string
    {
        return __('students.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('students.plural_label');
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('view_any_student') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('students.name'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('birth_date')
                    ->label(__('students.birth_date'))
                    ->required(),
                Forms\Components\Select::make('gender')
                    ->label(__('students.gender'))
                    ->options([
                        StudentGender::labels(),
                    ])
                    ->required(),
                Forms\Components\FileUpload::make('photo')
                    ->label(__('students.photo'))
                    ->image()
                    ->disk('public')
                    ->directory('students/photos')
                    ->required(),
                Forms\Components\Select::make('classroom_id')
                    ->label(__('students.classroom_id'))
                    ->relationship('classroom', 'name')
                    ->required(),
                Forms\Components\DatePicker::make('enrollment_date')
                    ->label(__('students.enrollment_date'))
                    ->required(),
                Forms\Components\TextInput::make('enrollment_status')
                    ->label(__('students.enrollment_status'))
                    ->maxLength(255),
                Forms\Components\Textarea::make('notes')
                    ->label(__('students.notes'))
                    ->columnSpanFull(),
                Select::make('fee_plan_id')
                    ->label(__('students.fee_plan'))
                    ->relationship('feePlan', 'name')  // ← علاقة مباشرة مع FeePlan
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('students.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('birth_date')
                    ->label(__('students.birth_date'))
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gender')
                    ->label(__('students.gender'))
                    ->searchable(),
                Tables\Columns\ImageColumn::make('photo')
                    ->label(__('students.photo'))
                    ->disk('public'),
                Tables\Columns\TextColumn::make('classroom.name')
                    ->label(__('students.classroom_id'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('enrollment_date')
                    ->label(__('students.enrollment_date'))
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('enrollment_status')
                    ->label(__('students.enrollment_status'))
                    ->searchable(),
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
                SelectFilter::make('classrooms')
                    ->label(__('admins.classrooms'))
                    ->relationship('classroom', 'name')
                    ->multiple()
                    ->preload(),
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}
