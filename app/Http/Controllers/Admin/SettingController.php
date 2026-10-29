<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->all();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $fields = [
            'brand_name',
            'brand_tagline',
            'brand_motto',
            'brand_description',
            'whatsapp_number',
            'instagram_handle',
            'instagram_url',
            'operational_hours',
            'pickup_address',
            'hero_title',
            'hero_subtitle',
        ];

        foreach ($fields as $field) {
            if ($request->has($field)) {
                Setting::set($field, $request->input($field), true);
            }
        }

        return back()->with('success', 'Pengaturan brand dan konten KopiZ berhasil diperbarui.');
    }
}
