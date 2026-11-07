<?php

use App\Http\Controllers\Account\StoreAccountController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Marketing\BlogController;
use App\Http\Controllers\Marketing\FeatureController;
use App\Http\Controllers\Marketing\HomeController;
use App\Http\Controllers\Marketing\PageController;
use App\Http\Controllers\Storefront\CartController;
use App\Http\Controllers\Storefront\CheckoutController;
use App\Http\Controllers\Storefront\ProductController;
use App\Http\Controllers\Storefront\ShopController;
use App\Http\Controllers\Storefront\StoreHomeController;
use App\Http\Controllers\Webhooks\PaymentSandboxWebhookController;
use App\Http\Controllers\Webhooks\ProviderMockWebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/webhooks/payments/sandbox', PaymentSandboxWebhookController::class);
Route::post('/webhooks/providers/mock', ProviderMockWebhookController::class);

Route::get('/', HomeController::class)->name('marketing.home');

Route::prefix('')->name('marketing.')->group(function (): void {
    Route::get('/about', [PageController::class, 'about'])->name('about');
    Route::get('/features', [FeatureController::class, 'index'])->name('features.index');
    Route::get('/features/{slug}', [FeatureController::class, 'show'])->name('features.show');
    Route::get('/pricing', [PageController::class, 'pricing'])->name('pricing');
    Route::get('/integrations', [PageController::class, 'integrations'])->name('integrations');
    Route::get('/enterprise', [PageController::class, 'enterprise'])->name('enterprise');
    Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
    Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
    Route::get('/docs', [PageController::class, 'docs'])->name('docs');
    Route::get('/api-docs', [PageController::class, 'apiDocs'])->name('api-docs');
    Route::get('/help', [PageController::class, 'help'])->name('help');
    Route::get('/faq', [PageController::class, 'faq'])->name('faq');
    Route::get('/contact', [PageController::class, 'contact'])->name('contact');
    Route::post('/contact', [PageController::class, 'submitContact'])->name('contact.submit');
    Route::get('/request-demo', [PageController::class, 'requestDemo'])->name('request-demo');
    Route::post('/request-demo', [PageController::class, 'submitDemo'])->name('request-demo.submit');
    Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
    Route::get('/terms', [PageController::class, 'terms'])->name('terms');
    Route::get('/refund-policy', [PageController::class, 'refundPolicy'])->name('refund-policy');
});

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::view('/account', 'account.portal')->middleware('auth')->name('customer.portal');

Route::middleware('auth')->group(function (): void {
    Route::get('/email/verify', [AuthController::class, 'verifyNotice'])->name('verification.notice');
    Route::get('/email/verify-stub', [AuthController::class, 'verifyStub'])->name('verification.stub');
});

Route::prefix('store/{storeSlug}')
    ->middleware('store.context')
    ->name('storefront.')
    ->group(function (): void {
        Route::get('/', StoreHomeController::class)->name('home');
        Route::get('/shop', [ShopController::class, 'index'])->name('shop');
        Route::get('/shop/category/{categorySlug}', [ShopController::class, 'category'])->name('shop.category');
        Route::get('/product/{productSlug}', [ProductController::class, 'show'])->name('product.show');
        Route::get('/product/{productSlug}/customize', [ProductController::class, 'customize'])->name('product.customize');
        Route::get('/cart', [CartController::class, 'index'])->name('cart');
        Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout');
        Route::get('/checkout/thank-you/{orderNumber}', [CheckoutController::class, 'thankYou'])->name('checkout.thank-you');

        Route::middleware('auth')->prefix('account')->name('account.')->group(function (): void {
            Route::get('/', [StoreAccountController::class, 'dashboard'])->name('dashboard');
            Route::get('/orders', [StoreAccountController::class, 'orders'])->name('orders');
            Route::get('/orders/{orderNumber}', [StoreAccountController::class, 'orderShow'])->name('orders.show');
            Route::get('/tracking', [StoreAccountController::class, 'tracking'])->name('tracking');
            Route::get('/returns', [StoreAccountController::class, 'returns'])->name('returns');
            Route::get('/profile', [StoreAccountController::class, 'profile'])->name('profile');
            Route::post('/profile', [StoreAccountController::class, 'updateProfile'])->name('profile.update');
            Route::get('/addresses', [StoreAccountController::class, 'addresses'])->name('addresses');
            Route::get('/support', [StoreAccountController::class, 'support'])->name('support');
        });
    });
