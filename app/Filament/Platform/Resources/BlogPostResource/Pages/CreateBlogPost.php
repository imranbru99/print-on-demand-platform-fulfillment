<?php

declare(strict_types=1);

namespace App\Filament\Platform\Resources\BlogPostResource\Pages;

use App\Filament\Platform\Resources\BlogPostResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBlogPost extends CreateRecord
{
    protected static string $resource = BlogPostResource::class;
}
