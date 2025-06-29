<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Hash;
use Filament\Pages\CreateRecord;
use Filament\Pages\Page;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\Action;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Users';
    protected static ?int $navigationSort = 2;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('email_verified_at'),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required(fn(Page $livewire) => $livewire instanceof CreateRecord) // Wajib diisi hanya saat membuat record baru
                    ->dehydrateStateUsing(fn($state) => filled($state) ? Hash::make($state) : null) // Hash password hanya jika diisi
                    ->dehydrated(fn($state) => filled($state)) // Hanya simpan jika diisi
                    ->maxLength(255)
                    ->rules(['nullable', 'min:6']),
                Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ]),
                Select::make('roles')
                    ->relationship('roles', 'name')
                    ->required()
                    ->default('member'),
                Forms\Components\FileUpload::make('photo_profile')
                    ->image()
                    ->disk('public')
                    ->directory('photo_profile'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->icon('heroicon-m-envelope')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('email_verified_at')
                //     ->dateTime()
                //     ->sortable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('photo_profile')
                    ->searchable(),
                // status
                Tables\Columns\TextColumn::make('status'),
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
                SelectFilter::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload(),
            ])
            ->actions([
                Action::make('Set Role')
                    ->icon('heroicon-m-adjustments-vertical')
                    ->form([
                        Select::make('role')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->required()
                            ->searchable()
                            ->preload()
                            ->optionsLimit(10)
                            ->getOptionLabelFromRecordUsing(fn($record) => $record->name),
                    ]),


                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->defaultSort('created_at', 'desc')

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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
