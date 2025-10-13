<?php

namespace App\Filament\Resources\Configs\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ConfigForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('key')
                    ->label(__('configs.fields.key'))
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->disabled(fn (?object $record) => $record !== null),

                TextInput::make('label')
                    ->label(__('configs.fields.label'))
                    ->required()
                    ->maxLength(255),

                Textarea::make('value')
                    ->label(__('configs.fields.value'))
                    ->rows(3)
                    ->maxLength(65535)
                    ->columnSpanFull(),

                Select::make('type')
                    ->label(__('configs.fields.type'))
                    ->options([
                        'text' => __('configs.types.text'),
                        'url' => __('configs.types.url'),
                        'email' => __('configs.types.email'),
                        'phone' => __('configs.types.phone'),
                        'textarea' => __('configs.types.textarea'),
                    ])
                    ->default('text')
                    ->required(),

                Textarea::make('description')
                    ->label(__('configs.fields.description'))
                    ->rows(2)
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }
}
