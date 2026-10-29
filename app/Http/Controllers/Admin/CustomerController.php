<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::withCount('orders')
            ->withSum(['orders' => function ($q) {
                $q->where('status', '!=', 'CANCELLED');
            }], 'total_amount');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                  ->orWhere('phone', 'like', "%{$q}%")
                  ->orWhere('instagram_handle', 'like', "%{$q}%");
            });
        }

        $customers = $query->orderBy('orders_count', 'desc')->paginate(15)->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }
}
