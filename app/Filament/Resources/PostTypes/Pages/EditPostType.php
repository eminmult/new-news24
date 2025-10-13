<?php

namespace App\Filament\Resources\PostTypes\Pages;

use App\Filament\Resources\PostTypes\PostTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPostType extends EditRecord
{
    protected static string $resource = PostTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
