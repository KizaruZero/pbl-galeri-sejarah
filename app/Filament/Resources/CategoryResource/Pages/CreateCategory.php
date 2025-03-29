<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;



class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;

    public function getCreatedNotification(): Notification
    {
        return Notification::make()
            ->title('Category Created')
            ->body('The category was created successfully.')
            ->icon('heroicon-o-check-circle')
            ->sendToDatabase(auth()->user())
            ->send();

    }
}


