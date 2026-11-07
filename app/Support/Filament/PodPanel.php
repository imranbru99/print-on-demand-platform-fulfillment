<?php

declare(strict_types=1);

namespace App\Support\Filament;

use App\Http\Middleware\SetTenantFromUser;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

final class PodPanel
{
    /**
     * @return array<string, string>
     */
    public static function colors(): array
    {
        return [
            'primary' => Color::Teal,
            'gray' => Color::Slate,
            'danger' => Color::Rose,
            'success' => Color::Emerald,
            'warning' => Color::Amber,
        ];
    }

    /**
     * @return list<class-string>
     */
    public static function middleware(): array
    {
        return [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            AuthenticateSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
            DisableBladeIconComponents::class,
            DispatchServingFilamentEvent::class,
        ];
    }

    /**
     * @return list<class-string>
     */
    public static function authMiddleware(): array
    {
        return [
            Authenticate::class,
        ];
    }

    /**
     * @return list<class-string>
     */
    public static function tenantMiddleware(): array
    {
        return array_merge(self::middleware(), [
            SetTenantFromUser::class,
        ]);
    }
}
