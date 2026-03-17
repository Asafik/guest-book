<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        return view('admin.setting.setting', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_name'          => 'required|string|max:255',
            'institution_name'  => 'required|string|max:255',
            'institution_short' => 'required|string|max:20',
            'year'              => 'required|string|max:10',
            'address'           => 'nullable|string',
            'description'       => 'nullable|string',
            'logo'              => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'favicon'           => 'nullable|mimes:jpg,jpeg,png,ico|max:1024',
        ]);

        $data    = $request->only([
            'app_name', 'institution_name', 'institution_short',
            'year', 'address', 'description'
        ]);

        $setting = Setting::first();

        if ($request->hasFile('logo')) {
            if ($setting && $setting->logo) {
                Storage::disk('public')->delete($setting->logo);
            }
            $data['logo'] = $request->file('logo')->store('setting', 'public');
        }

        if ($request->hasFile('favicon')) {
            if ($setting && $setting->favicon) {
                Storage::disk('public')->delete($setting->favicon);
            }
            $data['favicon'] = $request->file('favicon')->store('setting', 'public');
        }

        if ($setting) {
            $setting->update($data);
        } else {
            Setting::create($data);
        }

        return response()->json(['success' => true]);
    }
}
