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
    protected static ?string $navigationLabel = 'Settings';
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
                    ->description('These are the general information for the company. You can change the name of the company and the logo.')
                    ->schema([
                        TextInput::make('cms_name')
                            ->label('CMS Name')
                            ->required(),
                        FileUpload::make('logo')
                            ->label('Logo')
                            ->hint('Recommended image size: 100x100 pixels with a transparent background (png).')   
                            ->hintColor('warning')
                            ->image()
                            ->directory('company-profile'),
                        TextInput::make('cms_email')
                            ->label('CMS Email')
                            ->required(),
                        TextInput::make('cms_phone')
                            ->label('CMS Phone')
                            ->required(),
                        TextInput::make('cms_address')
                            ->label('CMS Address')
                            ->required(),
                    ])->columns(2),

                Section::make('Background Images')
                    ->description('These images will be used as background images for the home, events, gallery, and article sections. Recommended image size: 16:9 aspect ratio (e.g. 1920x1080 pixels)')
                    ->schema([
                        FileUpload::make('bg_home_1')
                            ->label('Home Background Slide 1')
                            ->image()
                            ->directory('company-profile/backgrounds'),
                        FileUpload::make('bg_home_2')
                            ->label('Home Background Slide 2')
                            ->image()
                            ->directory('company-profile/backgrounds'),
                        FileUpload::make('bg_home_3')
                            ->label('Home Background Slide 3')
                            ->image()
                            ->directory('company-profile/backgrounds'),
                        FileUpload::make('bg_events_1')
                            ->label('Events Background Slide 1')
                            ->image()
                            ->directory('company-profile/backgrounds'),
                        FileUpload::make('bg_events_2')
                            ->label('Events Background Slide 2')
                            ->image()
                            ->directory('company-profile/backgrounds'),
                        FileUpload::make('bg_events_3')
                            ->label('Events Background Slide 3')
                            ->image()
                            ->directory('company-profile/backgrounds'),
                        FileUpload::make('bg_gallery_1')
                            ->label('Gallery Background Slide 1')
                            ->image()
                            ->directory('company-profile/backgrounds'),
                        FileUpload::make('bg_gallery_2')
                            ->label('Gallery Background Slide 2')
                            ->image()
                            ->directory('company-profile/backgrounds'),
                        FileUpload::make('bg_gallery_3')
                            ->label('Gallery Background Slide 3')
                            ->image()
                            ->directory('company-profile/backgrounds'),
                        FileUpload::make('bg_article_1')
                            ->label('Article Background Slide 1')
                            ->image()
                            ->directory('company-profile/backgrounds'),
                        FileUpload::make('bg_article_2')
                            ->label('Article Background Slide 2')
                            ->image()
                            ->directory('company-profile/backgrounds'),
                        FileUpload::make('bg_article_3')
                            ->label('Article Background Slide 3')
                            ->image()
                            ->directory('company-profile/backgrounds'),
                        FileUpload::make('bg_member_1')
                            ->label('Member Background Slide 1')
                            ->image()
                            ->directory('company-profile/backgrounds'),
                        FileUpload::make('bg_member_2')
                            ->label('Member Background Slide 2')
                            ->image()
                            ->directory('company-profile/backgrounds'),
                        FileUpload::make('bg_member_3')
                            ->label('Member Background Slide 3')
                            ->image()
                            ->directory('company-profile/backgrounds'),
                    ])->columns(3),

                Section::make('Content')
                    ->description('These are the text for the home, events, gallery, and article sections. You can change the text to your own content.')
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
                        Textarea::make('member_text')
                            ->label('Member Text')
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