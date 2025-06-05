<?php

namespace App\Filament\Widgets;

use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class LastLoggedInUsers extends BaseWidget
{
    protected static ?string $heading = 'Last 10 Logged In Users';
    protected static ?int $sort = 13;


    public function getTableQuery(): Builder
    {
        return User::query()
            ->select('users.*', 'sessions.last_activity', 'sessions.user_agent', 'sessions.ip_address')
            ->join('sessions', 'users.id', '=', 'sessions.user_id')
            ->orderBy('sessions.last_activity', 'desc')
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
            \Filament\Tables\Columns\TextColumn::make('last_activity')
                ->label('Last Login')
                ->dateTime()
                ->sortable(),
            \Filament\Tables\Columns\TextColumn::make('user_agent')
                ->label('User Device')
                ->searchable(),
            \Filament\Tables\Columns\TextColumn::make('ip_address')
                ->label('IP Address')
                ->searchable(),
        ];
    }
}