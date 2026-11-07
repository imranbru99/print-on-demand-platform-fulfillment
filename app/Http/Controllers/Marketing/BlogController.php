<?php

declare(strict_types=1);

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class BlogController extends Controller
{
    public function index(): View
    {
        $posts = BlogPost::query()
            ->withoutGlobalScopes()
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->orderByDesc('published_at')
            ->paginate(12);

        return view('marketing.blog.index', compact('posts'));
    }

    public function show(string $slug): View
    {
        $post = BlogPost::query()
            ->withoutGlobalScopes()
            ->where('slug', $slug)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->first();

        if ($post === null) {
            throw new NotFoundHttpException();
        }

        return view('marketing.blog.show', compact('post'));
    }
}
