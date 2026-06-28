<?php

namespace App\Filament\Resources;

use App\Enums\PostType;
use App\Filament\Resources\ClassroomPostResource\Pages;
use App\Filament\Resources\ClassroomPostResource\RelationManagers;
use App\Models\ClassroomPost;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClassroomPostResource extends Resource
{
    protected static ?string $model = ClassroomPost::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getModelLabel(): string
    {
        return __('posts.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('posts.plural_label');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('classroom_id')
                ->label(__('posts.classroom'))
                ->relationship('classroom', 'name')
                ->required(),

            Select::make('teacher_id')
                ->label(__('posts.teacher'))
                ->relationship('teacher', 'name')
                ->nullable(),

            Select::make('type')
                ->label(__('posts.type'))
                ->options(PostType::class)
                ->required()
                ->live()
                ->afterStateUpdated(fn ($state, callable $set) =>
                    $set('is_admin_only', $state === PostType::AdminNote->value)
                ),

            DatePicker::make('date')
                ->label(__('posts.date'))
                ->required()
                ->default(now()),

            Textarea::make('content')
                ->label(__('posts.content'))
                ->rows(4),

            FileUpload::make('attachment')
                ->label(__('posts.attachment'))
                ->acceptedFileTypes(['application/pdf', 'image/*'])
                ->disk('public')
                ->directory('classroom-posts')
                ->maxSize(10240)
                ->visibility('public'),

            Toggle::make('is_admin_only')
                ->label(__('posts.admin_only'))
                ->helperText(__('posts.admin_only_hint')),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('classroom.name')
                    ->label(__('posts.classroom'))
                    ->searchable(),

                TextColumn::make('teacher.name')
                    ->label(__('posts.teacher'))
                    ->searchable(),

                TextColumn::make('type')
                    ->label(__('posts.type'))
                    ->badge()
                    ->color(fn (PostType $state) => $state->color()),

                TextColumn::make('date')
                    ->label(__('posts.date'))
                    ->date()
                    ->sortable(),

                TextColumn::make('content')
                    ->label(__('posts.content'))
                    ->limit(50),

                IconColumn::make('is_admin_only')
                    ->label(__('posts.admin_only'))
                    ->boolean(),

                TextColumn::make('attachment')
                    ->label(__('posts.attachment'))
                    ->icon('heroicon-o-paper-clip')
                    ->formatStateUsing(fn ($state) => $state ? __('posts.attachment') : null)
                    ->url(fn ($record) => $record->attachment ? asset('storage/' . $record->attachment) : null)
                    ->openUrlInNewTab(),
            ])
            ->defaultSort('date', 'desc')
            ->filters([
                SelectFilter::make('classroom')
                    ->relationship('classroom', 'name'),

                SelectFilter::make('type')
                    ->options(PostType::class),

                Tables\Filters\TernaryFilter::make('is_admin_only')
                    ->label(__('posts.admin_only')),
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
            'index' => Pages\ListClassroomPosts::route('/'),
            'create' => Pages\CreateClassroomPost::route('/create'),
            'edit' => Pages\EditClassroomPost::route('/{record}/edit'),
        ];
    }
}
