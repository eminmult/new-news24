<?php

namespace App\Filament\Resources\ActivityLogs\Pages;

use App\Filament\Resources\ActivityLogs\ActivityLogResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View as ViewComponent;
use Filament\Schemas\Schema;

class ViewActivityLog extends ViewRecord
{
    protected static string $resource = ActivityLogResource::class;

    public function infolist(Schema $schema): Schema
    {
        $record = $this->getRecord();

        return $schema
            ->columns(1)
            ->schema([
                ViewComponent::make('filament.resources.activity-logs.view-basic-info')
                    ->viewData(['record' => $record])
                    ->columnSpanFull(),

                ViewComponent::make('filament.resources.activity-logs.view-changes')
                    ->viewData(['record' => $record])
                    ->visible(fn () => $record->event === 'updated' && ($record->getOldValues() || $record->getNewValues()))
                    ->columnSpanFull(),

                ViewComponent::make('filament.resources.activity-logs.view-all-data')
                    ->viewData(['record' => $record])
                    ->visible(fn () => $record->event === 'created' || $record->event === 'deleted' || $record->event === 'restored')
                    ->columnSpanFull(),
            ]);
    }
}
