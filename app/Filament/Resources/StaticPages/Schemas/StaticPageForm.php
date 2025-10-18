<?php

namespace App\Filament\Resources\StaticPages\Schemas;

use App\Models\StaticPage;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
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

                Section::make('Dəyərlərimiz')
                    ->description('Bizim dəyərlərimiz seksiýası')
                    ->schema([
                        TextInput::make('content.mission.title')
                            ->label('Başlıq')
                            ->default('Bizim dəyərlərimiz')
                            ->columnSpanFull(),
                        Textarea::make('content.mission.description')
                            ->label('Açıqlama')
                            ->rows(2)
                            ->default('Portalın əsas prinsipləri dəqiqlik, operativlik və qərəzsizlikdir.')
                            ->columnSpanFull(),
                        Repeater::make('content.mission.cards')
                            ->label('Dəyərlər kartları')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Başlıq')
                                    ->required(),
                                Textarea::make('text')
                                    ->label('Mətn')
                                    ->rows(2)
                                    ->required(),
                            ])
                            ->defaultItems(4)
                            ->default([
                                ['title' => 'Dəqiqlik', 'text' => 'Yalnız yoxlanmış və etibarlı məlumatları paylaşırıq'],
                                ['title' => 'Operativlik', 'text' => 'Hadisələr baş verən kimi dərhal oxuculara çatdırırıq'],
                                ['title' => 'Qərəzsizlik', 'text' => 'Obyektiv mövqedən hadisələri işıqlandırırıq'],
                                ['title' => 'Çoxşaxəlilik', 'text' => 'Müxtəlif sahələrdən geniş xəbər spektri təqdim edirik']
                            ])
                            ->collapsible()
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Section::make('Təsisçi')
                    ->description('Təsisçi şirkət seksiýası')
                    ->schema([
                        TextInput::make('content.founder.title')
                            ->label('Başlıq')
                            ->default('Təsisçi')
                            ->columnSpanFull()
                            ->helperText('Təsisçi haqqında məlumat MainInfo cədvəlində saxlanılır'),
                    ])
                    ->collapsible(),

                Section::make('Redaksiya heyəti')
                    ->description('Komanda seksiýası')
                    ->schema([
                        TextInput::make('content.team.title')
                            ->label('Başlıq')
                            ->default('Redaksiya heyəti')
                            ->columnSpanFull(),
                        Textarea::make('content.team.description')
                            ->label('Açıqlama')
                            ->rows(2)
                            ->default('Peşəkar jurnalistlərdən ibarət komandamız')
                            ->columnSpanFull()
                            ->helperText('Komanda üzvləri User cədvəlindən avtomatik çəkilir (role=author)'),
                    ])
                    ->collapsible(),

                Section::make('Əlaqə')
                    ->description('Əlaqə seksiýası etiketləri')
                    ->schema([
                        TextInput::make('content.contact.title')
                            ->label('Seksiýa başlığı')
                            ->default('Bizimlə əlaqə')
                            ->columnSpanFull(),

                        TextInput::make('content.contact.address_label')
                            ->label('Ünvan etiketi')
                            ->default('Ünvan')
                            ->columnSpanFull(),

                        TextInput::make('content.contact.phone_label')
                            ->label('Telefon etiketi')
                            ->default('Telefon')
                            ->columnSpanFull(),

                        TextInput::make('content.contact.email_label')
                            ->label('Email etiketi')
                            ->default('E-mail')
                            ->columnSpanFull(),

                        TextInput::make('content.contact.map_title')
                            ->label('Xəritə başlığı')
                            ->default('Ofisimizin yeri')
                            ->columnSpanFull(),

                        TextInput::make('content.contact.cooperation_title')
                            ->label('Əməkdaşlıq başlığı')
                            ->default('Əməkdaşlıq')
                            ->columnSpanFull(),

                        Textarea::make('content.contact.copyright_note')
                            ->label('Müəllif hüquqları qeydi')
                            ->rows(2)
                            ->default('<strong>Qeyd:</strong> Saytdakı bütün materialların müəllif hüquqları qorunur. Materiallardan istifadə edərkən mənbəyə istinad mütləqdir.')
                            ->columnSpanFull()
                            ->helperText('HTML dəstəklənir'),

                        Textarea::make('content.contact.map_embed_url')
                            ->label('Google Maps Embed URL')
                            ->rows(3)
                            ->default('https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3039.0876582088947!2d49.83873731562238!3d40.38294207936527!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40307d9c8c6c5555%3A0x1234567890abcdef!2sMikhail%20Koverochkin%20Street%2038%2C%20Baku%2C%20Azerbaijan!5e0!3m2!1sen!2saz!4v1234567890123!5m2!1sen!2saz')
                            ->columnSpanFull()
                            ->helperText('Google Maps-dan "Embed a map" URL-ni daxil edin')
                            ->hint('Əlaqə məlumatları (telefon, email, ünvan, əməkdaşlıq mətni) MainInfo cədvəlində saxlanılır'),
                    ])
                    ->collapsible(),
            ]);
    }
}
