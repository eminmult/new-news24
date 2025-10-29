<?php

namespace App\Filament\Pages;

use App\Models\User;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class RobotsTxtSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    public static function getNavigationLabel(): string
    {
        return __('robots.navigation_label');
    }

    public function getTitle(): string
    {
        return __('robots.title');
    }

    protected static ?int $navigationSort = 97;

    protected string $view = 'filament.pages.robots-txt-settings';

    public static function canAccess(): bool
    {
        return auth()->user()?->role === User::ROLE_ADMIN;
    }

    public ?array $data = [];

    public function mount(): void
    {
        $robotsPath = public_path('robots.txt');
        $content = file_exists($robotsPath) ? file_get_contents($robotsPath) : '';

        $this->form->fill([
            'content' => $content,
        ]);
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('robots.actions.save'))
                ->submit('save'),
        ];
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->components([
                Section::make(__('robots.sections.editor'))
                    ->description(__('robots.sections.editor_description'))
                    ->schema([
                        Textarea::make('content')
                            ->label(__('robots.fields.content'))
                            ->required()
                            ->rows(20)
                            ->helperText(__('robots.fields.content_helper'))
                            ->extraAttributes([
                                'style' => 'font-family: monospace; font-size: 14px;'
                            ]),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();

            $robotsPath = public_path('robots.txt');

            // Создаем резервную копию в storage
            if (file_exists($robotsPath)) {
                $backupDir = storage_path('app/backups');
                if (!is_dir($backupDir)) {
                    mkdir($backupDir, 0755, true);
                }
                $backupPath = $backupDir . '/robots.txt.' . date('Y-m-d_His') . '.backup';
                copy($robotsPath, $backupPath);
            }

            // Сохраняем новый контент
            $result = file_put_contents($robotsPath, $data['content']);

            if ($result === false) {
                throw new \Exception('Failed to write robots.txt file');
            }

            // Очищаем Response Cache (Full Page Cache)
            if (class_exists('\Spatie\ResponseCache\Facades\ResponseCache')) {
                \Spatie\ResponseCache\Facades\ResponseCache::clear();
            }

            // Очищаем Laravel кэш
            \Illuminate\Support\Facades\Cache::flush();

            Notification::make()
                ->success()
                ->title(__('robots.notifications.saved_title'))
                ->body(__('robots.notifications.saved_body'))
                ->send();

        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title('Ошибка')
                ->body('Не удалось сохранить файл: ' . $e->getMessage())
                ->send();

            \Log::error('RobotsTxtSettings save error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
