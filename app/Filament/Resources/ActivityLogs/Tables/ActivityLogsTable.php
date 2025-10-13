<?php

namespace App\Filament\Resources\ActivityLogs\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ActivityLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label(__('activity-logs.table.columns.id'))
                    ->sortable()
                    ->searchable(),

                TextColumn::make('event')
                    ->label(__('activity-logs.table.columns.event'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'created' => 'success',
                        'updated' => 'warning',
                        'deleted' => 'danger',
                        'restored' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => __('activity-logs.events.' . $state))
                    ->sortable(),

                TextColumn::make('log_name')
                    ->label(__('activity-logs.table.columns.section'))
                    ->badge()
                    ->color('gray')
                    ->formatStateUsing(fn (?string $state): string => $state ? __('activity-logs.sections.' . $state) : '-')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('subject_type')
                    ->label(__('activity-logs.table.columns.model'))
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(fn (?string $state): string => $state ? __('activity-logs.models.' . class_basename($state)) : '-')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('description')
                    ->label(__('activity-logs.table.columns.description'))
                    ->limit(50)
                    ->searchable()
                    ->wrap(),

                TextColumn::make('causer.name')
                    ->label(__('activity-logs.table.columns.causer'))
                    ->default(__('activity-logs.system'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('subject_id')
                    ->label(__('activity-logs.table.columns.subject_id'))
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('ip_address')
                    ->label(__('activity-logs.table.columns.ip_address'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label(__('activity-logs.table.columns.created_at'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->since()
                    ->description(fn ($record) => $record->created_at->format('d.m.Y H:i:s')),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('event')
                    ->label(__('activity-logs.filters.event'))
                    ->options([
                        'created' => __('activity-logs.events.created'),
                        'updated' => __('activity-logs.events.updated'),
                        'deleted' => __('activity-logs.events.deleted'),
                        'restored' => __('activity-logs.events.restored'),
                    ]),

                SelectFilter::make('log_name')
                    ->label(__('activity-logs.filters.log_name'))
                    ->options([
                        'post' => __('activity-logs.models.post'),
                        'category' => __('activity-logs.models.category'),
                        'tag' => __('activity-logs.models.tag'),
                        'user' => __('activity-logs.models.user'),
                        'posttype' => __('activity-logs.models.posttype'),
                        'staticpage' => __('activity-logs.models.staticpage'),
                    ]),

                SelectFilter::make('causer_id')
                    ->label(__('activity-logs.filters.causer'))
                    ->relationship('causer', 'name')
                    ->searchable()
                    ->preload(),

                Filter::make('created_at')
                    ->label(__('activity-logs.filters.date_range'))
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('created_from')
                            ->label(__('activity-logs.filters.from')),
                        \Filament\Forms\Components\DatePicker::make('created_until')
                            ->label(__('activity-logs.filters.until')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->bulkActions([])
            ->paginated([10, 25, 50, 100]);
    }
}
