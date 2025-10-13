<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class DateRangeFilter extends Widget implements HasForms
{
    use InteractsWithForms;

    protected int | string | array $columnSpan = 'full';
    protected static bool $isLazy = false;

    public static function canView(): bool
    {
        return Auth::user()?->role === User::ROLE_ADMIN;
    }

    public ?array $data = [];
    public ?string $currentStartDate = null;
    public ?string $currentEndDate = null;

    public function mount(): void
    {
        $startDate = request()->query('start_date');
        $endDate = request()->query('end_date');

        // Если даты не указаны, устанавливаем последние 7 дней по умолчанию
        if (!$startDate || !$endDate) {
            $startDate = now()->subDays(6)->format('Y-m-d');
            $endDate = now()->format('Y-m-d');
        }

        $this->currentStartDate = $startDate;
        $this->currentEndDate = $endDate;

        $this->form->fill([
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            DatePicker::make('start_date')
                ->label('C')
                ->placeholder('дд.мм.гггг')
                ->native(false)
                ->displayFormat('d.m.Y')
                ->maxDate(fn ($get) => $get('end_date')),

            DatePicker::make('end_date')
                ->label('По')
                ->placeholder('дд.мм.гггг')
                ->native(false)
                ->displayFormat('d.m.Y')
                ->minDate(fn ($get) => $get('start_date')),
        ];
    }

    protected function getFormStatePath(): string
    {
        return 'data';
    }

    protected function getDashboardUrl(array $params = []): string
    {
        $url = \Filament\Facades\Filament::getUrl();
        return $params ? $url . '?' . http_build_query($params) : $url;
    }

    public function setToday(): void
    {
        $url = $this->getDashboardUrl([
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->format('Y-m-d'),
        ]);
        $this->js("window.location.href = '{$url}'");
    }

    public function setYesterday(): void
    {
        $url = $this->getDashboardUrl([
            'start_date' => now()->subDay()->format('Y-m-d'),
            'end_date' => now()->subDay()->format('Y-m-d'),
        ]);
        $this->js("window.location.href = '{$url}'");
    }

    public function setLast7Days(): void
    {
        $url = $this->getDashboardUrl([
            'start_date' => now()->subDays(6)->format('Y-m-d'),
            'end_date' => now()->format('Y-m-d'),
        ]);
        $this->js("window.location.href = '{$url}'");
    }

    public function setLastMonth(): void
    {
        $url = $this->getDashboardUrl([
            'start_date' => now()->subDays(29)->format('Y-m-d'),
            'end_date' => now()->format('Y-m-d'),
        ]);
        $this->js("window.location.href = '{$url}'");
    }

    public function setAllTime(): void
    {
        $url = $this->getDashboardUrl([
            'start_date' => '2000-01-01',
            'end_date' => now()->format('Y-m-d'),
        ]);
        $this->js("window.location.href = '{$url}'");
    }

    public function resetFilter(): void
    {
        $url = $this->getDashboardUrl();
        $this->js("window.location.href = '{$url}'");
    }

    public function applyFilter(): void
    {
        $data = $this->form->getState();

        if ($data['start_date'] && $data['end_date']) {
            $url = $this->getDashboardUrl([
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
            ]);
            $this->js("window.location.href = '{$url}'");
        }
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        // Перечитываем из URL каждый раз при рендере
        $startDate = request()->query('start_date');
        $endDate = request()->query('end_date');

        // Если даты не указаны, устанавливаем последние 7 дней по умолчанию
        if (!$startDate || !$endDate) {
            $startDate = now()->subDays(6)->format('Y-m-d');
            $endDate = now()->format('Y-m-d');
        }

        // Обновляем свойства
        $this->currentStartDate = $startDate;
        $this->currentEndDate = $endDate;

        return view('filament.widgets.date-range-filter', [
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }
}
