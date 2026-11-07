<?php

declare(strict_types=1);

namespace App\Filament\Platform\Resources\BlogPostResource\Pages;

use App\Filament\Platform\Resources\BlogPostResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBlogPosts extends ListRecords
{
    protected static string $resource = BlogPostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(true),
        ];
    }
}
