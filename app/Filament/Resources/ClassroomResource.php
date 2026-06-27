<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClassroomResource\Pages;
  use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
 use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use App\Services\ClassroomService;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Select;
use App\Models\Classroom;
use Filament\Tables\Filters\SelectFilter;

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
        
            ])
       ->actions([
    Tables\Actions\EditAction::make(),
    
    Tables\Actions\Action::make('transfer_students')
        ->label(__('classrooms.transfer_students'))
        ->icon('heroicon-o-arrow-right-circle')
        ->color('warning')
        ->form([
            Select::make('target_classroom_id')
                ->label(__('classrooms.target_classroom'))
                ->options(fn ($record) => 
                    Classroom::where('id', '!=', $record->id)
                        ->pluck('name', 'id')
                )
                ->required(),
        ])
        ->action(function ($record, array $data) {
            $service = new ClassroomService();
            $result  = $service->transferStudents($record, $data['target_classroom_id']);

            if (!$result['success']) {
                Notification::make()
                    ->title(__("classrooms.{$result['message']}"))
                    ->danger()
                    ->send();
                return;
            }

            Notification::make()
                ->title(__('classrooms.transfer_success'))
                ->body("تم نقل {$result['count']} طالب بنجاح")
                ->success()
                ->send();
        }),
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
