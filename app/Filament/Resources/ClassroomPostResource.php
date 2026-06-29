<?php

namespace App\Filament\Resources;

use App\Enums\PostType;
use App\Filament\Resources\ClassroomPostResource\Pages;
use App\Models\ClassroomPost;
use App\Models\Classroom;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
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

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('view_any_post') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema(function () {
            $user = auth()->user();
            $isTeacher = $user && $user->hasRole('teacher');
            $adminId = $isTeacher ? $user->id : null;

            $schema = [];

            if ($isTeacher) {
                $schema[] = Hidden::make('admin_id')->default($adminId);
            } else {
                $schema[] = Select::make('admin_id')
                    ->label(__('posts.admin'))
                    ->relationship('admin', 'name')
                    ->nullable();
            }

            $schema[] = Select::make('classroom_id')
                ->label(__('posts.classroom'))
                ->options(function () use ($isTeacher, $adminId) {
                    $query = Classroom::query();
                    if ($isTeacher && $adminId) {
                        $query->whereHas('admins', fn ($q) => $q->where('admins.id', $adminId));
                    }
                    return $query->pluck('name', 'id');
                })
                ->required();

            $schema[] = Select::make('type')
                ->label(__('posts.type'))
                ->options(PostType::class)
                ->required()
                ->live()
                ->afterStateUpdated(fn ($state, callable $set) =>
                    $set('is_admin_only', $state === PostType::AdminNote->value)
                );

            $schema[] = DatePicker::make('date')
                ->label(__('posts.date'))
                ->required()
                ->default(now());

            $schema[] = Textarea::make('content')
                ->label(__('posts.content'))
                ->rows(4);

            $schema[] = FileUpload::make('attachment')
                ->label(__('posts.attachment'))
                ->acceptedFileTypes(['application/pdf', 'image/*'])
                ->disk('public')
                ->directory('classroom-posts')
                ->maxSize(10240)
                ->visibility('public');

            $schema[] = Toggle::make('is_admin_only')
                ->label(__('posts.admin_only'))
                ->helperText(__('posts.admin_only_hint'));

            return $schema;
        });
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $user = auth()->user();
                if ($user && $user->hasRole('teacher')) {
                    return $query->whereHas('classroom', fn ($q) => $q->whereHas('admins', fn ($aq) => $aq->where('admins.id', $user->id)));
                }
                return $query;
            })
            ->columns([
                TextColumn::make('classroom.name')
                    ->label(__('posts.classroom'))
                    ->searchable(),

                TextColumn::make('admin.name')
                    ->label(__('posts.admin'))
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
