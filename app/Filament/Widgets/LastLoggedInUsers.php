<?php

namespace App\Filament\Widgets;

use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;

class LastLoggedInUsers extends BaseWidget
{
    protected static ?string $heading = 'Last 10 Logged In Users';
    protected static ?int $sort = 13;

    public function getTableQuery(): Builder
    {
        return User::query()
            ->whereNotNull('last_login_at')
            ->orderBy('last_login_at', 'desc')
            ->limit(10);
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
            \Filament\Tables\Columns\TextColumn::make('last_login_at')
                ->label('Last Login')
                ->dateTime()
                ->sortable(),
            \Filament\Tables\Columns\TextColumn::make('last_user_agent')
                ->label('User Device')
                ->searchable()
                ->limit(50),
            \Filament\Tables\Columns\TextColumn::make('last_login_ip')
                ->label('IP Address')
                ->searchable(),
        ];
    }
}