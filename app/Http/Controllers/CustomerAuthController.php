<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerAuthController extends Controller
{
    private function getCommonSettings(): array
    {
        return Setting::pluck('value', 'key')->all();
    }

    public function showRegisterForm()
    {
        if (Auth::guard('customer')->check()) {
            return redirect()->route('customer.orders');
        }

        $settings = $this->getCommonSettings();
        return view('public.auth.register', compact('settings'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:100',
            'password' => 'required|string|min:6|confirmed',
            'instagram_handle' => 'nullable|string|max:50',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'phone.required' => 'Nomor WhatsApp wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $rawPhone = $request->phone;
        $normalizedPhone = preg_replace('/[^0-9]/', '', $rawPhone);
        if (str_starts_with($normalizedPhone, '0')) {
            $normalizedPhone = '62' . substr($normalizedPhone, 1);
        }

        // Cari atau buat customer berdasarkan normalized_phone
        $customer = Customer::where('normalized_phone', $normalizedPhone)->first();

        if ($customer) {
            $customer->name = trim($request->name);
            $customer->phone = $rawPhone;
            if ($request->filled('email')) {
                $customer->email = trim($request->email);
            }
            if ($request->filled('instagram_handle')) {
                $customer->instagram_handle = trim($request->instagram_handle);
            }
            $customer->password = Hash::make($request->password);
            $customer->save();
        } else {
            $customer = Customer::create([
                'name' => trim($request->name),
                'phone' => $rawPhone,
                'normalized_phone' => $normalizedPhone,
                'email' => $request->filled('email') ? trim($request->email) : null,
                'instagram_handle' => $request->filled('instagram_handle') ? trim($request->instagram_handle) : null,
                'password' => Hash::make($request->password),
            ]);
        }

        Auth::guard('customer')->login($customer, true);

        return redirect()->intended(route('customer.orders'))
            ->with('success', "Selamat datang kak {$customer->name}! Akun KopiZ Anda berhasil dibuat.");
    }

    public function showLoginForm()
    {
        if (Auth::guard('customer')->check()) {
            return redirect()->route('customer.orders');
        }

        $settings = $this->getCommonSettings();
        return view('public.auth.login', compact('settings'));
    }

    public function login(Request $request)
    {
        $request->validate([
            'login_id' => 'required|string',
            'password' => 'required|string',
        ], [
            'login_id.required' => 'Nomor WhatsApp atau Email wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $loginId = trim($request->login_id);
        $customer = null;

        if (filter_var($loginId, FILTER_VALIDATE_EMAIL)) {
            $customer = Customer::where('email', $loginId)->first();
        } else {
            $normalized = preg_replace('/[^0-9]/', '', $loginId);
            if (str_starts_with($normalized, '0')) {
                $normalized = '62' . substr($normalized, 1);
            }
            $customer = Customer::where('normalized_phone', $normalized)
                ->orWhere('phone', $loginId)
                ->first();
        }

        if ($customer && $customer->password && Hash::check($request->password, $customer->password)) {
            Auth::guard('customer')->login($customer, $request->boolean('remember'));
            return redirect()->intended(route('customer.orders'))
                ->with('success', "Selamat datang kembali, {$customer->name}!");
        }

        return back()->withErrors([
            'login_id' => 'Nomor WhatsApp / Email atau Password salah. Jika belum punya password, silakan daftar akun baru.'
        ])->withInput($request->except('password'));
    }

    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah berhasil keluar.');
    }

    public function myOrders()
    {
        $customer = Auth::guard('customer')->user();
        if (!$customer) {
            return redirect()->route('customer.login');
        }

        $settings = $this->getCommonSettings();
        $orders = Order::where('customer_id', $customer->id)
            ->orWhereHas('customer', function ($q) use ($customer) {
                $q->where('normalized_phone', $customer->normalized_phone);
            })
            ->with(['items.product', 'preOrder'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('public.customer.orders', compact('settings', 'customer', 'orders'));
    }

    public function trackLookup(Request $request)
    {
        $settings = $this->getCommonSettings();
        $q = trim($request->query('q', ''));
        $orders = null;

        if (!empty($q)) {
            // Cek apakah kode pesanan (misal KPZ-...)
            if (str_starts_with(strtoupper($q), 'KPZ-')) {
                $order = Order::where('order_code', strtoupper($q))->first();
                if ($order) {
                    return redirect()->route('order.track', $order->order_code);
                }
            }

            // Jika berupa nomor telepon
            $cleanPhone = preg_replace('/[^0-9]/', '', $q);
            if (!empty($cleanPhone)) {
                $normPhone = $cleanPhone;
                if (str_starts_with($normPhone, '0')) {
                    $normPhone = '62' . substr($normPhone, 1);
                }

                $orders = Order::where(function ($query) use ($q, $normPhone, $cleanPhone) {
                    $query->where('order_code', 'like', "%{$q}%")
                        ->orWhereHas('customer', function ($c) use ($normPhone, $cleanPhone, $q) {
                            $c->where('normalized_phone', $normPhone)
                                ->orWhere('phone', 'like', "%{$cleanPhone}%")
                                ->orWhere('name', 'like', "%{$q}%");
                        });
                })->with(['items', 'customer'])->orderBy('created_at', 'desc')->take(20)->get();
            } else {
                $orders = Order::where('order_code', 'like', "%{$q}%")
                    ->orWhereHas('customer', function ($c) use ($q) {
                        $c->where('name', 'like', "%{$q}%");
                    })->with(['items', 'customer'])->orderBy('created_at', 'desc')->take(20)->get();
            }
        }

        return view('public.track_lookup', compact('settings', 'q', 'orders'));
    }
}
