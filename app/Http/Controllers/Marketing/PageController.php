<?php

declare(strict_types=1);

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use App\Models\Faq;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class PageController extends Controller
{
    public function about(): View
    {
        return $this->cmsOr('about', 'marketing.pages.about');
    }

    public function enterprise(): View
    {
        return $this->cmsOr('enterprise', 'marketing.pages.enterprise');
    }

    public function integrations(): View
    {
        return view('marketing.pages.integrations', [
            'modules' => array_filter(
                config('pod.modules', []),
                fn (string $key): bool => in_array($key, ['integrations', 'automation', 'print-providers'], true),
                ARRAY_FILTER_USE_KEY,
            ),
        ]);
    }

    public function pricing(): View
    {
        return view('marketing.pages.pricing', [
            'plans' => Plan::query()->where('is_active', true)->orderBy('price')->get(),
        ]);
    }

    public function docs(): View
    {
        return view('marketing.pages.docs');
    }

    public function apiDocs(): View
    {
        return view('marketing.pages.api-docs');
    }

    public function help(): View
    {
        return view('marketing.pages.help');
    }

    public function faq(): View
    {
        $faqs = Faq::query()->withoutGlobalScopes()->orderBy('sort_order')->limit(50)->get();

        return view('marketing.pages.faq', [
            'faqs' => $faqs,
            'fallbackFaqs' => $this->fallbackFaqs(),
        ]);
    }

    public function privacy(): View
    {
        return $this->cmsOr('privacy', 'marketing.legal.privacy');
    }

    public function terms(): View
    {
        return $this->cmsOr('terms', 'marketing.legal.terms');
    }

    public function refundPolicy(): View
    {
        return $this->cmsOr('refund-policy', 'marketing.legal.refund-policy');
    }

    public function contact(Request $request): View
    {
        return view('marketing.pages.contact');
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        return back()->with('success', 'Thanks '.$validated['name'].', we received your message and will respond shortly.');
    }

    public function requestDemo(Request $request): View
    {
        return view('marketing.pages.request-demo');
    }

    public function submitDemo(Request $request)
    {
        $request->validate([
            'company' => ['required', 'string', 'max:160'],
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'volume' => ['nullable', 'string', 'max:80'],
        ]);

        return back()->with('success', 'Demo request received. Our team will reach out within one business day.');
    }

    private function cmsOr(string $slug, string $fallbackView): View
    {
        $page = CmsPage::query()
            ->withoutGlobalScopes()
            ->where('slug', $slug)
            ->where('is_published', true)
            ->first();

        if ($page !== null) {
            return view('marketing.cms', ['page' => $page]);
        }

        return view($fallbackView);
    }

    /**
     * @return list<array{q: string, a: string}>
     */
    private function fallbackFaqs(): array
    {
        return [
            ['q' => 'What is print-on-demand?', 'a' => 'Products are produced after an order is placed, reducing inventory risk and enabling custom designs per order.'],
            ['q' => 'Can I use my own domain?', 'a' => 'Yes. Stores support path-based URLs today and domain mapping is built for production rollouts.'],
            ['q' => 'How does pricing validation work?', 'a' => 'Checkout always reconciles line totals on the server using the pricing engine—client amounts are never trusted.'],
            ['q' => 'Is there a sandbox for payments?', 'a' => 'Yes. Demo and development environments use the sandbox payment gateway for safe testing.'],
        ];
    }
}
