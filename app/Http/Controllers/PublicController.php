<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\PreOrder;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PublicController extends Controller
{
    protected function getCommonSettings()
    {
        try {
            return Setting::pluck('value', 'key')->all();
        } catch (\Throwable $e) {
            return [];
        }
    }

    public function home()
    {
        $settings = $this->getCommonSettings();
        try {
            $featuredProducts = Product::active()->where('is_featured', true)->take(6)->get();
            if ($featuredProducts->isEmpty()) {
                $featuredProducts = Product::active()->take(6)->get();
            }
        } catch (\Throwable $e) {
            $featuredProducts = collect();
        }

        try {
            $activePreOrders = PreOrder::where('status', 'OPEN')
                ->where(function ($q) {
                    $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
                })
                ->where(function ($q) {
                    $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
                })
                ->take(2)
                ->get();
        } catch (\Throwable $e) {
            $activePreOrders = collect();
        }

        return view('public.home', compact('settings', 'featuredProducts', 'activePreOrders'));
    }

    public function menu(Request $request)
    {
        $settings = $this->getCommonSettings();
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();

        $query = Product::active();

        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_low':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('price', 'desc');
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                default:
                    $query->orderBy('is_featured', 'desc')->orderBy('name', 'asc');
            }
        } else {
            $query->orderBy('is_featured', 'desc')->orderBy('name', 'asc');
        }

        $products = $query->paginate(12)->withQueryString();

        return view('public.menu', compact('settings', 'categories', 'products'));
    }

    public function menuDetail($slug)
    {
        $settings = $this->getCommonSettings();
        $product = Product::active()->where('slug', $slug)->firstOrFail();
        $relatedProducts = Product::active()
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->take(3)
            ->get();

        return view('public.menu_detail', compact('settings', 'product', 'relatedProducts'));
    }

    public function preOrders()
    {
        $settings = $this->getCommonSettings();
        $preOrders = PreOrder::whereIn('status', ['OPEN', 'SCHEDULED', 'SOLD_OUT'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('public.pre_orders', compact('settings', 'preOrders'));
    }

    public function preOrderDetail($slug)
    {
        $settings = $this->getCommonSettings();
        $preOrder = PreOrder::with('products')->where('slug', $slug)->firstOrFail();

        return view('public.pre_order_detail', compact('settings', 'preOrder'));
    }

    public function cart()
    {
        $settings = $this->getCommonSettings();
        return view('public.cart', compact('settings'));
    }

    public function checkout(Request $request)
    {
        $settings = $this->getCommonSettings();
        $preOrderId = $request->query('pre_order_id');
        $preOrder = null;

        if ($preOrderId) {
            $preOrder = PreOrder::with('products')->find($preOrderId);
        }

        return view('public.checkout', compact('settings', 'preOrder'));
    }

    public function processCheckout(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'required|string|max:20',
            'pickup_at' => 'nullable|string|max:100',
            'customer_notes' => 'nullable|string|max:500',
            'order_type' => 'required|in:REGULAR,PRE_ORDER',
            'pre_order_id' => 'nullable|exists:pre_orders,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1|max:50',
        ]);

        return DB::transaction(function () use ($request) {
            $rawPhone = $request->customer_phone;
            $normalizedPhone = preg_replace('/[^0-9]/', '', $rawPhone);
            if (str_starts_with($normalizedPhone, '0')) {
                $normalizedPhone = '62' . substr($normalizedPhone, 1);
            }

            // Deduplikasi Customer
            $customer = Customer::firstOrCreate(
                ['normalized_phone' => $normalizedPhone],
                [
                    'name' => trim($request->customer_name),
                    'phone' => $rawPhone,
                    'instagram_handle' => $request->instagram_handle,
                ]
            );

            // Validasi Pre-Order jika pesanan jenis PO
            $preOrder = null;
            $totalUnits = 0;
            foreach ($request->items as $item) {
                $totalUnits += (int)$item['quantity'];
            }

            if ($request->order_type === 'PRE_ORDER') {
                if (!$request->pre_order_id) {
                    return back()->withErrors(['msg' => 'Pre-Order batch wajib dipilih.'])->withInput();
                }

                // Pessimistic Locking untuk anti race-condition kuota
                $preOrder = PreOrder::where('id', $request->pre_order_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($preOrder->status !== 'OPEN') {
                    return back()->withErrors(['msg' => 'Pre-order saat ini sedang tidak dibuka.'])->withInput();
                }

                if ($preOrder->ends_at && now()->gt($preOrder->ends_at)) {
                    return back()->withErrors(['msg' => 'Batas waktu pre-order ini telah berakhir.'])->withInput();
                }

                if (($preOrder->reserved_units + $totalUnits) > $preOrder->quota_units) {
                    return back()->withErrors([
                        'msg' => "Sisa kuota Pre-Order hanya tersisa {$preOrder->remainingQuota()} unit. Kuantitas pesanan Anda melebihi kuota."
                    ])->withInput();
                }

                // Tambahkan kuota terpesan
                $preOrder->reserved_units += $totalUnits;
                if ($preOrder->reserved_units >= $preOrder->quota_units) {
                    $preOrder->status = 'SOLD_OUT';
                }
                $preOrder->save();
            }

            // Hitung Ulang Total & Validasi Stok Produk
            $subtotal = 0;
            $orderItemsData = [];

            foreach ($request->items as $item) {
                // Lock product row to prevent price/stock drift
                $product = Product::where('id', $item['product_id'])->lockForUpdate()->firstOrFail();

                if ($product->status === 'SOLD_OUT' || !$product->is_active) {
                    return back()->withErrors(['msg' => "Produk {$product->name} saat ini tidak tersedia atau habis."])->withInput();
                }

                $quantity = (int)$item['quantity'];

                // Harga dari database (atau pivot PO jika PO)
                $unitPrice = $product->price;
                if ($preOrder) {
                    $pivot = $preOrder->products()->where('products.id', $product->id)->first();
                    if ($pivot && $pivot->pivot->price > 0) {
                        $unitPrice = $pivot->pivot->price;
                    }
                }

                $itemSubtotal = $unitPrice * $quantity;
                $subtotal += $itemSubtotal;

                $orderItemsData[] = [
                    'product_id' => $product->id,
                    'product_name_snapshot' => $product->name,
                    'sku_snapshot' => $product->sku,
                    'unit_price' => $unitPrice,
                    'quantity' => $quantity,
                    'subtotal' => $itemSubtotal,
                ];
            }

            $orderCode = 'KPZ-' . now()->format('ymd') . '-' . strtoupper(Str::random(5));

            $order = Order::create([
                'order_code' => $orderCode,
                'customer_id' => $customer->id,
                'order_type' => $request->order_type,
                'pre_order_id' => $preOrder ? $preOrder->id : null,
                'order_date' => now(),
                'pickup_at' => $request->pickup_at ?? ($preOrder ? $preOrder->pickup_at?->format('d M Y, H:i') : 'Langsung di kedai'),
                'subtotal' => $subtotal,
                'discount_amount' => 0,
                'total_amount' => $subtotal,
                'status' => 'PENDING',
                'customer_notes' => $request->customer_notes,
            ]);

            foreach ($orderItemsData as $itemData) {
                $order->items()->create($itemData);
            }

            // Catat history
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'from_status' => null,
                'to_status' => 'PENDING',
                'note' => 'Pesanan baru dibuat oleh pelanggan melalui checkout web.',
                'created_at' => now(),
            ]);

            return redirect()->route('order.track', ['orderCode' => $order->order_code])
                ->with('success', 'Pesanan Anda berhasil dibuat! Silakan hubungi WhatsApp kami untuk konfirmasi.');
        });
    }

    public function trackOrder($orderCode)
    {
        $settings = $this->getCommonSettings();
        $order = Order::with(['items', 'customer', 'preOrder', 'statusHistories'])
            ->where('order_code', $orderCode)
            ->firstOrFail();

        // Siapkan pesan WhatsApp sesuai PRD Bagian 9
        $itemsText = "";
        foreach ($order->items as $item) {
            $formattedSubtotal = 'Rp' . number_format($item->subtotal, 0, ',', '.');
            $itemsText .= "{$item->product_name_snapshot} x {$item->quantity} — {$formattedSubtotal}\n";
        }

        $formattedTotal = 'Rp' . number_format($order->total_amount, 0, ',', '.');
        $orderTypeLabel = $order->order_type === 'PRE_ORDER' ? 'Pre-Order' : 'Reguler';
        $pickupTime = $order->pickup_at ?: '-';
        $notes = $order->customer_notes ?: '-';

        $waMessage = "Halo KopiZ! Saya ingin memesan.\n\n"
            . "Kode Pesanan: {$order->order_code}\n"
            . "Nama: {$order->customer->name}\n"
            . "Nomor WhatsApp: {$order->customer->phone}\n\n"
            . "Pesanan:\n"
            . "{$itemsText}\n"
            . "Total: {$formattedTotal}\n"
            . "Jenis: {$orderTypeLabel}\n"
            . "Pengambilan: {$pickupTime}\n"
            . "Catatan: {$notes}\n\n"
            . "Mohon konfirmasi pesanan saya. Terima kasih.";

        $adminPhone = $settings['whatsapp_number'] ?? '6281234567890';
        $adminPhoneClean = preg_replace('/[^0-9]/', '', $adminPhone);
        if (str_starts_with($adminPhoneClean, '0')) {
            $adminPhoneClean = '62' . substr($adminPhoneClean, 1);
        }

        $waLink = "https://wa.me/{$adminPhoneClean}?text=" . urlencode($waMessage);

        return view('public.order_track', compact('settings', 'order', 'waLink', 'waMessage'));
    }

    public function about()
    {
        $settings = $this->getCommonSettings();
        return view('public.about', compact('settings'));
    }

    public function howToOrder()
    {
        $settings = $this->getCommonSettings();
        return view('public.how_to_order', compact('settings'));
    }

    public function contact()
    {
        $settings = $this->getCommonSettings();
        return view('public.contact', compact('settings'));
    }

    public function privacy()
    {
        $settings = $this->getCommonSettings();
        return view('public.privacy', compact('settings'));
    }

    public function terms()
    {
        $settings = $this->getCommonSettings();
        return view('public.terms', compact('settings'));
    }
}
