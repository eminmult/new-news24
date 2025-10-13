<?php

namespace App\Filament\Resources\Authors;

use App\Filament\Resources\Authors\Pages\ManageAuthors;
use App\Models\User;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class AuthorResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    public static function getModelLabel(): string
    {
        return __('authors.resource.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('authors.resource.plural_label');
    }

    public static function getNavigationLabel(): string
    {
        return __('authors.resource.navigation_label');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('authors.resource.navigation_group');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('authors.fields.name'))
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($set, ?string $state) => $set('slug', Str::slug($state))),
                TextInput::make('slug')
                    ->label(__('authors.fields.slug'))
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('email')
                    ->label(__('users.fields.email'))
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true),
                Select::make('role')
                    ->label(__('roles.fields.role'))
                    ->options(User::getRoles())
                    ->required()
                    ->default(User::ROLE_AUTHOR)
                    ->selectablePlaceholder(false),
                Toggle::make('is_active')
                    ->label(__('users.fields.is_active'))
                    ->helperText(__('users.fields.is_active_helper'))
                    ->default(true)
                    ->inline(false),
                TextInput::make('password')
                    ->label(__('users.fields.password'))
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => !empty($state) ? bcrypt($state) : null)
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create')
                    ->maxLength(255)
                    ->helperText(__('users.fields.password_helper')),
                SpatieMediaLibraryFileUpload::make('avatar')
                    ->collection('avatar')
                    ->label(__('authors.fields.avatar'))
                    ->image()
                    ->imageEditor()
                    ->maxSize(5120)
                    ->helperText(__('authors.fields.avatar_helper')),
                Textarea::make('bio')
                    ->label(__('authors.fields.bio'))
                    ->rows(4)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('avatar')
                    ->collection('avatar')
                    ->conversion('thumb')
                    ->label(__('authors.table.columns.avatar'))
                    ->circular(),
                TextColumn::make('name')
                    ->label(__('authors.table.columns.name'))
                    ->searchable(),
                TextColumn::make('slug')
                    ->label(__('authors.table.columns.slug'))
                    ->searchable(),
                TextColumn::make('role')
                    ->label(__('roles.fields.role'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => User::getRoles()[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'success',
                        'editor' => 'warning',
                        'author' => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('posts_count')
                    ->label(__('authors.table.columns.posts_count'))
                    ->counts('posts')
                    ->sortable()
                    ->badge()
                    ->color('info'),
                IconColumn::make('is_active')
                    ->label(__('users.fields.is_active'))
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('authors.table.columns.created_at'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('authors.table.columns.updated_at'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('is_active')
                    ->label(__('users.fields.is_active'))
                    ->options([
                        1 => __('users.status.active'),
                        0 => __('users.status.inactive'),
                    ]),
                SelectFilter::make('role')
                    ->label(__('roles.fields.role'))
                    ->options(User::getRoles()),
            ])
            ->recordActions([
                EditAction::make()
                    ->label(__('authors.table.actions.edit')),
            ])
            ->toolbarActions([
                // Bulk actions removed - users cannot be deleted
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageAuthors::route('/'),
        ];
    }
}
