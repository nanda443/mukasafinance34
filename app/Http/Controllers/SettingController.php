<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->keyBy('key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        // 1. Validasi input
        $validated = $request->validate([
            'system_name' => 'nullable|string|max:255',
            'system_title' => 'nullable|string|max:255',
            'favicon' => 'nullable|image|mimes:ico,png,gif,jpg,jpeg|max:512',
            'system_logo' => 'nullable|image|mimes:jpeg,png,gif,jpg|max:2048',
            'login_bg_image' => 'nullable|image|mimes:jpeg,png,gif,jpg|max:5120'
        ]);

        // 2. Update Text Settings
        $inputs = $request->except(['_token', '_method', 'favicon', 'system_logo', 'login_bg_image']);
        
        foreach ($inputs as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'type' => 'text']
            );
        }

        // 3. Update File Settings
        $files = ['favicon', 'system_logo', 'login_bg_image'];

        foreach ($files as $key) {
            if ($request->hasFile($key)) {
                $file = $request->file($key);
                
                if ($file && $file->isValid()) {
                    try {
                        $oldSetting = Setting::where('key', $key)->first();
                        
                        // Delete old file
                        if ($oldSetting && !empty($oldSetting->value)) {
                            $oldPath = $oldSetting->value;
                            if (is_string($oldPath) && trim($oldPath) !== '') {
                                try {
                                    $physicalPath = public_path('uploads/' . $oldPath);
                                    if (file_exists($physicalPath)) {
                                        unlink($physicalPath);
                                    }
                                } catch (\Exception $e) {
                                    \Illuminate\Support\Facades\Log::warning("Could not delete old file: " . $e->getMessage());
                                }
                            }
                        }

                        // Ensure directory exists
                        $uploadDir = public_path('uploads/settings');
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0755, true);
                        }

                        // Store new file directly to public folder
                        $filename = $key . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                        $file->move($uploadDir, $filename);
                        
                        $relativePath = 'settings/' . $filename;
                        
                        Setting::updateOrCreate(
                            ['key' => $key],
                            ['value' => $relativePath, 'type' => 'image']
                        );
                        
                    } catch (\Throwable $e) {
                        return redirect()->back()->withErrors(['file_error' => "Gagal menyimpan file $key: " . $e->getMessage()])->withInput();
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui!');
    }

    public function clearCache()
    {
        try {
            \Illuminate\Support\Facades\Artisan::call('optimize:clear');
            return redirect()->back()->with('success', 'Cache sistem berhasil dibersihkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membersihkan cache: ' . $e->getMessage());
        }
    }
}


