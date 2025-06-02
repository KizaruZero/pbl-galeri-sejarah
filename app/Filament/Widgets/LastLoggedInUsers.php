<?php

namespace App\Filament\Widgets;

use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class LastLoggedInUsers extends BaseWidget
{
    protected static ?string $heading = 'Last 10 Logged In Users';
    protected static ?int $sort = 1;

    public function getTableQuery(): Builder
    {
        return User::query()
            ->select('users.*', 'sessions.last_activity')
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
        ];
    }
}