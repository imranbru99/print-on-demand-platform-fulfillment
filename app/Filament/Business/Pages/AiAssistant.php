<?php

declare(strict_types=1);

namespace App\Filament\Business\Pages;

use App\Services\AiHubService;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;

class AiAssistant extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    protected static string $view = 'filament.business.pages.ai-assistant';

    protected static ?string $navigationGroup = 'Tools';

    protected static ?string $title = 'AI Assistant';

    public ?string $prompt = '';

    public ?string $reply = null;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Textarea::make('prompt')
                    ->label('Ask PrintFlow AI')
                    ->rows(5)
                    ->required(),
            ]);
    }

    public function ask(AiHubService $aiHub): void
    {
        $prompt = trim((string) $this->prompt);

        if ($prompt === '') {
            return;
        }

        $this->reply = $aiHub->chat($prompt);
    }
}
