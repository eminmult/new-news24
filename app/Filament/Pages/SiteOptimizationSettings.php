<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use App\Models\User;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class SiteOptimizationSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAdjustmentsHorizontal;

    public static function getNavigationLabel(): string
    {
        return __('optimization.navigation_label');
    }

    public function getTitle(): string
    {
        return __('optimization.title');
    }

    protected static ?int $navigationSort = 98;

    protected string $view = 'filament.pages.site-optimization-settings';

    public static function canAccess(): bool
    {
        return auth()->user()?->role === User::ROLE_ADMIN;
    }

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'home_posts_count' => Setting::get('home_posts_count', 15),
            'search_posts_count' => Setting::get('search_posts_count', 12),
            'category_posts_count' => Setting::get('category_posts_count', 12),
            'tag_posts_count' => Setting::get('tag_posts_count', 12),
            'slider_posts_count' => Setting::get('slider_posts_count', 5),
            'related_posts_count' => Setting::get('related_posts_count', 6),
            'trending_posts_count' => Setting::get('trending_posts_count', 5),
        ]);
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('optimization.actions.save'))
                ->submit('save'),
        ];
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->components([
                Section::make(__('optimization.sections.counts'))
                    ->description(__('optimization.sections.counts_description'))
                    ->schema([
                        TextInput::make('home_posts_count')
                            ->label(__('optimization.fields.home_posts_count'))
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->maxValue(100)
                            ->default(15),

                        TextInput::make('search_posts_count')
                            ->label(__('optimization.fields.search_posts_count'))
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->maxValue(100)
                            ->default(12),

                        TextInput::make('category_posts_count')
                            ->label(__('optimization.fields.category_posts_count'))
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->maxValue(100)
                            ->default(12),

                        TextInput::make('tag_posts_count')
                            ->label(__('optimization.fields.tag_posts_count'))
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->maxValue(100)
                            ->default(12),

                        TextInput::make('slider_posts_count')
                            ->label(__('optimization.fields.slider_posts_count'))
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->maxValue(20)
                            ->default(5),

                        TextInput::make('related_posts_count')
                            ->label(__('optimization.fields.related_posts_count'))
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->maxValue(20)
                            ->default(6),

                        TextInput::make('trending_posts_count')
                            ->label(__('optimization.fields.trending_posts_count'))
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->maxValue(20)
                            ->default(5),
                    ])->columns(2),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        Setting::set('home_posts_count', $data['home_posts_count'], 'numeric');
        Setting::set('search_posts_count', $data['search_posts_count'], 'numeric');
        Setting::set('category_posts_count', $data['category_posts_count'], 'numeric');
        Setting::set('tag_posts_count', $data['tag_posts_count'], 'numeric');
        Setting::set('slider_posts_count', $data['slider_posts_count'], 'numeric');
        Setting::set('related_posts_count', $data['related_posts_count'], 'numeric');
        Setting::set('trending_posts_count', $data['trending_posts_count'], 'numeric');

        Notification::make()
            ->success()
            ->title(__('optimization.notifications.saved_title'))
            ->body(__('optimization.notifications.saved_body'))
            ->send();
    }
}
