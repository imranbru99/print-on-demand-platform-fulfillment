<?php

declare(strict_types=1);

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class FeatureController extends Controller
{
    public function index(): View
    {
        $modules = config('pod.modules', []);

        return view('marketing.features.index', compact('modules'));
    }

    public function show(string $slug): View
    {
        $modules = config('pod.modules', []);

        if (! array_key_exists($slug, $modules)) {
            throw new NotFoundHttpException();
        }

        $module = $modules[$slug];

        $page = CmsPage::query()
            ->withoutGlobalScopes()
            ->where('slug', 'feature-'.$slug)
            ->where('is_published', true)
            ->first();

        return view('marketing.features.show', [
            'slug' => $slug,
            'module' => $module,
            'cms' => $page,
        ]);
    }
}
