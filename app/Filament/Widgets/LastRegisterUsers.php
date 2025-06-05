<?php

namespace App\Filament\Widgets;

use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class LastRegisterUsers extends BaseWidget
{
    protected static ?string $heading = 'Last 10 Created Users';
    protected static ?int $sort = 12;

    public function getTableQuery(): Builder
    {
        return User::query()
            ->orderBy('created_at', 'desc')
            ->limit(5);
    }

    protected function getTableColumns(): array
    {
        return [
            \Filament\Tables\Columns\TextColumn::make('name')
                ->label('Name')
                ->searchable(),
            \Filament\Tables\Columns\TextColumn::make('email')
                ->label('Email')
                ->searchable(),
            \Filament\Tables\Columns\TextColumn::make('created_at')
                ->label('Registered at'),
        ];
    }
}