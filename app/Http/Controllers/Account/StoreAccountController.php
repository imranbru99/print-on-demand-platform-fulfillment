<?php

declare(strict_types=1);

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderReturn;
use App\Models\Shipment;
use App\Models\Store;
use App\Models\SupportTicket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

final class StoreAccountController extends Controller
{
    public function dashboard(Request $request): View
    {
        $store = $this->store($request);
        $customer = $this->customerForUser($store);

        return view('account.dashboard', compact('store', 'customer'));
    }

    public function orders(Request $request): View
    {
        $store = $this->store($request);
        $customer = $this->customerForUser($store);

        $orders = $customer
            ? Order::query()->where('store_id', $store->getKey())->where('customer_id', $customer->getKey())->orderByDesc('created_at')->paginate(15)
            : collect();

        return view('account.orders.index', compact('store', 'customer', 'orders'));
    }

    public function orderShow(Request $request, string $storeSlug, string $orderNumber): View
    {
        $store = $this->store($request);
        $order = Order::query()->where('store_id', $store->getKey())->where('number', $orderNumber)->with('items')->firstOrFail();

        return view('account.orders.show', compact('store', 'order'));
    }

    public function tracking(Request $request): View
    {
        $store = $this->store($request);
        $customer = $this->customerForUser($store);

        $shipments = collect();
        if ($customer) {
            $shipments = Shipment::query()->limit(20)->get();
        }

        return view('account.tracking', compact('store', 'shipments'));
    }

    public function returns(Request $request): View
    {
        $store = $this->store($request);
        $customer = $this->customerForUser($store);

        $returns = $customer
            ? OrderReturn::query()->whereHas('order', fn ($q) => $q->where('customer_id', $customer->getKey()))->limit(20)->get()
            : collect();

        return view('account.returns', compact('store', 'returns'));
    }

    public function profile(Request $request): View
    {
        $store = $this->store($request);
        $customer = $this->customerForUser($store);

        return view('account.profile', compact('store', 'customer'));
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $store = $this->store($request);
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:80'],
            'last_name' => ['required', 'string', 'max:80'],
            'phone' => ['nullable', 'string', 'max:40'],
        ]);

        $user = Auth::user();
        $user?->forceFill(['name' => trim($validated['first_name'].' '.$validated['last_name']), 'phone' => $validated['phone'] ?? null])->save();

        $customer = $this->customerForUser($store);
        if ($customer !== null) {
            $customer->update([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
            ]);
        }

        return back()->with('success', 'Profile updated.');
    }

    public function addresses(Request $request): View
    {
        $store = $this->store($request);
        $customer = $this->customerForUser($store);
        $addresses = $customer ? CustomerAddress::query()->where('customer_id', $customer->getKey())->get() : collect();

        return view('account.addresses', compact('store', 'customer', 'addresses'));
    }

    public function support(Request $request): View
    {
        $store = $this->store($request);
        $customer = $this->customerForUser($store);
        $tickets = $customer
            ? SupportTicket::query()->where('customer_id', $customer->getKey())->orderByDesc('created_at')->limit(10)->get()
            : collect();

        return view('account.support', compact('store', 'tickets'));
    }

    private function store(Request $request): Store
    {
        /** @var Store $store */
        $store = $request->attributes->get('store');

        return $store;
    }

    private function customerForUser(Store $store): ?Customer
    {
        $user = Auth::user();
        if ($user === null) {
            return null;
        }

        return Customer::query()
            ->where('email', $user->email)
            ->first();
    }
}
