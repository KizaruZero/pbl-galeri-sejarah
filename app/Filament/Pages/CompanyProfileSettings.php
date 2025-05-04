<?php

namespace App\Filament\Pages;

use App\Models\CompanyProfile;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Concerns\InteractsWithNotifications;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use App\Models\User;

class CompanyProfileSettings extends Page implements HasForms
{
    use InteractsWithForms;
    use InteractsWithActions;


    protected static string $view = 'filament.pages.company-profile-settings';
    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?string $navigationLabel = 'Company Profile';
    protected static ?string $title = 'Company Profile Settings';

    public User $loggedInUser;


    public ?array $data = [];

    public function mount(): void
    {
        $companyProfile = CompanyProfile::firstOrCreate([]);
        $this->form->fill($companyProfile->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('General Information')
                    ->schema([
                        TextInput::make('cms_name')
                            ->label('CMS Name')
                            ->required(),
                        FileUpload::make('logo')
                            ->image()
                            ->directory('company-profile'),
                    ])->columns(2),

                Section::make('Background Images')
                    ->schema([
                        FileUpload::make('bg_home')
                            ->label('Home Background')
                            ->image()
                            ->directory('company-profile/backgrounds'),
                        FileUpload::make('bg_events')
                            ->label('Events Background')
                            ->image()
                            ->directory('company-profile/backgrounds'),
                        FileUpload::make('bg_gallery')
                            ->label('Gallery Background')
                            ->image()
                            ->directory('company-profile/backgrounds'),
                        FileUpload::make('bg_article')
                            ->label('Article Background')
                            ->image()
                            ->directory('company-profile/backgrounds'),
                    ])->columns(2),

                Section::make('Content')
                    ->schema([
                        Textarea::make('events_text')
                            ->label('Events Text')
                            ->columnSpanFull(),
                        Textarea::make('gallery_text')
                            ->label('Gallery Text')
                            ->columnSpanFull(),
                        Textarea::make('article_text')
                            ->label('Article Text')
                            ->columnSpanFull(),
                    ]),
            ])
            ->statePath('data')
            ->model(CompanyProfile::class);
    }

    public function save(): void
    {
        $data = $this->form->getState();
        CompanyProfile::first()->update($data);
        Notification::make()
            ->success()
            ->title('Company profile updated successfully')
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save changes')
                ->submit('save'),
        ];
    }


}