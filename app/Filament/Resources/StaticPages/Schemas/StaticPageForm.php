<?php

namespace App\Filament\Resources\StaticPages\Schemas;

use App\Models\StaticPage;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StaticPageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make(__('static-pages.sections.basic_info'))
                    ->schema([
                        TextInput::make('title')
                            ->label(__('static-pages.fields.title'))
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        TextInput::make('slug')
                            ->label(__('static-pages.fields.slug'))
                            ->required()
                            ->unique(table: StaticPage::class, ignoreRecord: true)
                            ->maxLength(255)
                            ->helperText(__('static-pages.fields.slug_helper'))
                            ->disabled(fn (string $operation) => $operation === 'edit')
                            ->dehydrated()
                            ->columnSpanFull(),
                        Toggle::make('is_active')
                            ->label(__('static-pages.fields.is_active'))
                            ->default(true)
                            ->columnSpanFull(),
                    ]),

                Section::make(__('static-pages.sections.hero'))
                    ->schema([
                        KeyValue::make('content.hero')
                            ->label(__('static-pages.fields.hero'))
                            ->keyLabel(__('static-pages.fields.key'))
                            ->valueLabel(__('static-pages.fields.value'))
                            ->default([
                                'title' => 'OLAY.AZ - Olay Olacaq!',
                                'subtitle' => 'Azərbaycanın əyləncə və mədəniyyət portalı',
                            ])
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Section::make(__('static-pages.sections.story'))
                    ->schema([
                        TextInput::make('content.story.title')
                            ->label(__('static-pages.fields.heading'))
                            ->default('Haqqımızda')
                            ->columnSpanFull(),
                        Textarea::make('content.story.lead')
                            ->label(__('static-pages.fields.lead_text'))
                            ->rows(2)
                            ->default('OLAY.AZ - 4 oktyabr 2021-ci ildə "Liatris Holding" şirkəti tərəfindən yaradılmış onlayn qəzetdir.')
                            ->columnSpanFull(),
                        Repeater::make('content.story.paragraphs')
                            ->label(__('static-pages.fields.paragraphs'))
                            ->schema([
                                Textarea::make('text')
                                    ->label(__('static-pages.fields.paragraph_text'))
                                    ->rows(3)
                                    ->required(),
                            ])
                            ->defaultItems(2)
                            ->collapsible()
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Section::make(__('static-pages.sections.mission'))
                    ->schema([
                        TextInput::make('content.mission.title')
                            ->label(__('static-pages.fields.heading'))
                            ->default('Məqsədimiz')
                            ->columnSpanFull(),
                        Textarea::make('content.mission.description')
                            ->label(__('static-pages.fields.description'))
                            ->rows(2)
                            ->default('Azərbaycanın mədəni yeniliklərini, əyləncə dünyasının ən maraqlı xəbərlərini və həyat tərzi mövzularını peşəkar şəkildə işıqlandırmaqdır.')
                            ->columnSpanFull(),
                        Repeater::make('content.mission.cards')
                            ->label(__('static-pages.fields.cards'))
                            ->schema([
                                TextInput::make('title')
                                    ->label(__('static-pages.fields.heading'))
                                    ->required(),
                                Textarea::make('text')
                                    ->label(__('static-pages.fields.text'))
                                    ->rows(2)
                                    ->required(),
                            ])
                            ->defaultItems(3)
                            ->collapsible()
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Section::make(__('static-pages.sections.stats'))
                    ->schema([
                        Repeater::make('content.stats')
                            ->label(__('static-pages.fields.counters'))
                            ->schema([
                                TextInput::make('value')
                                    ->label(__('static-pages.fields.counter_value'))
                                    ->numeric()
                                    ->required(),
                                TextInput::make('label')
                                    ->label(__('static-pages.fields.label'))
                                    ->required(),
                            ])
                            ->defaultItems(4)
                            ->default([
                                ['value' => 3, 'label' => 'İllik Təcrübə'],
                                ['value' => 5000, 'label' => 'Yayımlanmış Xəbər'],
                                ['value' => 1200000, 'label' => 'Aylıq Oxucu'],
                                ['value' => 50, 'label' => 'Mütəmadi Müsahibələr'],
                            ])
                            ->collapsible()
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Section::make(__('static-pages.sections.team'))
                    ->schema([
                        TextInput::make('content.team.title')
                            ->label(__('static-pages.fields.heading'))
                            ->default('Komandamız')
                            ->columnSpanFull(),
                        Textarea::make('content.team.description')
                            ->label(__('static-pages.fields.description'))
                            ->rows(2)
                            ->default('Peşəkar və təcrübəli komandamız ilə oxucularımıza ən yaxşı məzmunu təqdim edirik')
                            ->columnSpanFull(),
                        Repeater::make('content.team.members')
                            ->label(__('static-pages.fields.members'))
                            ->schema([
                                TextInput::make('name')
                                    ->label(__('static-pages.fields.name'))
                                    ->required(),
                                TextInput::make('position')
                                    ->label(__('static-pages.fields.position'))
                                    ->required(),
                                FileUpload::make('photo')
                                    ->label(__('static-pages.fields.photo'))
                                    ->image()
                                    ->imageEditor()
                                    ->maxSize(5120)
                                    ->directory('team')
                                    ->disk('public')
                                    ->visibility('public'),
                                TextInput::make('social_instagram')
                                    ->label(__('static-pages.fields.instagram_url'))
                                    ->url()
                                    ->nullable(),
                            ])
                            ->defaultItems(2)
                            ->collapsible()
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Section::make(__('static-pages.sections.timeline'))
                    ->schema([
                        TextInput::make('content.timeline.title')
                            ->label(__('static-pages.fields.heading'))
                            ->default('Bizim Tariximiz')
                            ->columnSpanFull(),
                        Repeater::make('content.timeline.events')
                            ->label(__('static-pages.fields.events'))
                            ->schema([
                                TextInput::make('date')
                                    ->label(__('static-pages.fields.date'))
                                    ->required(),
                                TextInput::make('title')
                                    ->label(__('static-pages.fields.heading'))
                                    ->required(),
                                Textarea::make('text')
                                    ->label(__('static-pages.fields.description'))
                                    ->rows(2)
                                    ->required(),
                            ])
                            ->defaultItems(4)
                            ->default([
                                ['date' => '4 Oktyabr 2021', 'title' => 'Başlanğıc', 'text' => 'OLAY.AZ "Liatris Holding" şirkəti tərəfindən yaradıldı'],
                                ['date' => '14 Noyabr 2022', 'title' => 'Müstəqillik', 'text' => 'Portal müstəqil fəaliyyətə başladı'],
                                ['date' => '2023-2024', 'title' => 'Böyümə', 'text' => 'Oxucu auditoriyası artdı, yeni formatlar və layihələr həyata keçirildi'],
                                ['date' => '2025', 'title' => 'Gələcək', 'text' => 'Yeni dizayn, genişlənmiş məzmun və daha çox yeniliklər'],
                            ])
                            ->collapsible()
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                // Contact page sections
                Section::make(__('static-pages.sections.contact_cards'))
                    ->schema([
                        Repeater::make('content.contact_cards')
                            ->label(__('static-pages.fields.contact_cards'))
                            ->schema([
                                TextInput::make('icon')
                                    ->label(__('static-pages.fields.icon'))
                                    ->helperText('location, phone, email')
                                    ->required(),
                                TextInput::make('title')
                                    ->label(__('static-pages.fields.heading'))
                                    ->required(),
                                Textarea::make('lines_text')
                                    ->label(__('static-pages.fields.lines_text'))
                                    ->rows(3)
                                    ->helperText(__('static-pages.fields.lines_helper'))
                                    ->required()
                                    ->columnSpanFull(),
                                TextInput::make('link')
                                    ->label(__('static-pages.fields.link'))
                                    ->helperText(__('static-pages.fields.link_helper'))
                                    ->nullable(),
                            ])
                            ->defaultItems(3)
                            ->mutateDehydratedStateUsing(function ($state) {
                                if (is_array($state)) {
                                    foreach ($state as &$item) {
                                        if (isset($item['lines_text'])) {
                                            $item['lines'] = array_filter(array_map('trim', explode("\n", $item['lines_text'])));
                                            unset($item['lines_text']);
                                        }
                                    }
                                }
                                return $state;
                            })
                            ->afterStateHydrated(function ($component, $state) {
                                if (is_array($state)) {
                                    $newState = [];
                                    foreach ($state as $item) {
                                        if (isset($item['lines']) && is_array($item['lines'])) {
                                            $item['lines_text'] = implode("\n", $item['lines']);
                                        }
                                        $newState[] = $item;
                                    }
                                    $component->state($newState);
                                }
                            })
                            ->default([
                                [
                                    'icon' => 'location',
                                    'title' => 'Ünvan',
                                    'lines_text' => "Old Town Plaza\nBəşir Səfəroğlu küçəsi, 123\nBakı, Azərbaycan",
                                ],
                                [
                                    'icon' => 'phone',
                                    'title' => 'Telefon',
                                    'lines_text' => '+994 99 270 77 77',
                                    'link' => 'tel:+994992707777',
                                ],
                                [
                                    'icon' => 'email',
                                    'title' => 'Email',
                                    'lines_text' => 'info@olay.az',
                                    'link' => 'mailto:info@olay.az',
                                ],
                            ])
                            ->collapsible()
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Section::make(__('static-pages.sections.social_media'))
                    ->schema([
                        TextInput::make('content.social.title')
                            ->label(__('static-pages.fields.heading'))
                            ->default('Sosial Şəbəkələr')
                            ->columnSpanFull(),
                        TextInput::make('content.social.subtitle')
                            ->label(__('static-pages.fields.subtitle'))
                            ->default('Bizi sosial şəbəkələrdə izləyin')
                            ->columnSpanFull(),
                        Repeater::make('content.social.links')
                            ->label(__('static-pages.fields.social_links'))
                            ->schema([
                                TextInput::make('platform')
                                    ->label(__('static-pages.fields.platform'))
                                    ->required(),
                                TextInput::make('url')
                                    ->label(__('static-pages.fields.url'))
                                    ->url()
                                    ->required(),
                                TextInput::make('class')
                                    ->label(__('static-pages.fields.css_class'))
                                    ->helperText(__('static-pages.fields.css_class_helper'))
                                    ->required(),
                            ])
                            ->defaultItems(5)
                            ->default([
                                ['platform' => 'Instagram', 'url' => 'https://www.instagram.com/olay.az_official/', 'class' => 'instagram'],
                                ['platform' => 'Facebook', 'url' => 'https://www.facebook.com/olayofficial', 'class' => 'facebook'],
                                ['platform' => 'YouTube', 'url' => 'https://www.youtube.com/channel/UCAorrSTGj8vBM4R9lIYfdXw', 'class' => 'youtube'],
                                ['platform' => 'TikTok', 'url' => 'https://www.tiktok.com/@olayazofficial', 'class' => 'tiktok'],
                                ['platform' => 'Telegram', 'url' => 'https://t.me/olayaz', 'class' => 'telegram'],
                            ])
                            ->collapsible()
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Section::make(__('static-pages.sections.working_hours'))
                    ->schema([
                        TextInput::make('content.hours.title')
                            ->label(__('static-pages.fields.heading'))
                            ->default('İş Saatları')
                            ->columnSpanFull(),
                        Repeater::make('content.hours.schedule')
                            ->label(__('static-pages.fields.schedule'))
                            ->schema([
                                TextInput::make('day')
                                    ->label(__('static-pages.fields.day'))
                                    ->required(),
                                TextInput::make('time')
                                    ->label(__('static-pages.fields.time'))
                                    ->required(),
                                Toggle::make('active')
                                    ->label(__('static-pages.fields.active'))
                                    ->default(true),
                            ])
                            ->defaultItems(3)
                            ->default([
                                ['day' => 'Bazar ertəsi - Cümə', 'time' => '09:00 - 18:00', 'active' => true],
                                ['day' => 'Şənbə', 'time' => '10:00 - 15:00', 'active' => true],
                                ['day' => 'Bazar', 'time' => 'Bağlı', 'active' => false],
                            ])
                            ->collapsible()
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Section::make(__('static-pages.sections.map'))
                    ->schema([
                        TextInput::make('content.map.title')
                            ->label(__('static-pages.fields.heading'))
                            ->default('Bizim Ünvan')
                            ->columnSpanFull(),
                        Textarea::make('content.map.embed_url')
                            ->label(__('static-pages.fields.embed_url'))
                            ->rows(3)
                            ->default('https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3039.5285897866916!2d49.85279!3d40.37797!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNDDCsDIyJzQwLjciTiA0OcKwNTEnMTAuMCJF!5e0!3m2!1sen!2saz!4v1234567890123!5m2!1sen!2saz')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
            ]);
    }
}
