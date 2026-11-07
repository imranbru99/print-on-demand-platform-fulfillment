<?php

declare(strict_types=1);

namespace App\Filament\Platform\Resources\BlogPostResource\Pages;

use App\Filament\Platform\Resources\BlogPostResource;
use Filament\Resources\Pages\EditRecord;

class EditBlogPost extends EditRecord
{
    protected static string $resource = BlogPostResource::class;
}
