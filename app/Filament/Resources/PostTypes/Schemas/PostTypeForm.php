<?php

namespace App\Filament\Resources\PostTypes\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('post-types.sections.basic_info'))
                    ->schema([
                        TextInput::make('name')
                            ->label(__('post-types.fields.name'))
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($set, ?string $state) => $set('slug', Str::slug($state)))
                            ->columnSpanFull(),
                        TextInput::make('slug')
                            ->label(__('post-types.fields.slug'))
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->columnSpanFull(),
                        TextInput::make('icon')
                            ->label(__('post-types.fields.icon'))
                            ->helperText(__('post-types.fields.icon_helper'))
                            ->columnSpanFull(),
                        ColorPicker::make('color')
                            ->label(__('post-types.fields.color'))
                            ->default('#6b7280')
                            ->columnSpanFull(),
                    ]),
                Section::make(__('post-types.sections.settings'))
                    ->schema([
                        Toggle::make('is_active')
                            ->label(__('post-types.fields.is_active'))
                            ->default(true)
                            ->columnSpanFull(),
                        TextInput::make('order')
                            ->label(__('post-types.fields.order'))
                            ->numeric()
                            ->default(0)
                            ->helperText(__('post-types.fields.order_helper'))
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
