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
        // 1. Update Text Settings
        $inputs = $request->except(['_token', '_method', 'favicon', 'system_logo', 'login_bg_image']);
        
        foreach ($inputs as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'type' => 'text']
            );
        }

        // 2. Update File Settings
        $files = ['favicon', 'system_logo', 'login_bg_image'];

        foreach ($files as $key) {
            if ($request->hasFile($key)) {
                $file = $request->file($key);
                \Illuminate\Support\Facades\Log::info("Processing file: $key");
                
                // Validate file
                if ($file->isValid()) {
                    try {
                        $oldSetting = Setting::where('key', $key)->first();
                        
                        // Delete old file using public disk
                        if ($oldSetting && !empty($oldSetting->value)) {
                            $oldPath = $oldSetting->value;
                            if (is_string($oldPath) && trim($oldPath) !== '') {
                                if (Storage::disk('public')->exists($oldPath)) {
                                    Storage::disk('public')->delete($oldPath);
                                    \Illuminate\Support\Facades\Log::info("Deleted old file: $oldPath");
                                }
                            }
                        }

                        // Store new file to public disk using putFile
                        $path = Storage::disk('public')->putFile('settings', $file);
                        
                        if ($path) {
                            \Illuminate\Support\Facades\Log::info("File saved to: $path");
                            Setting::updateOrCreate(
                                ['key' => $key],
                                ['value' => $path, 'type' => 'image']
                            );
                        } else {
                            \Illuminate\Support\Facades\Log::error("Failed to save file: $key");
                        }
                    } catch (\Throwable $e) {
                         \Illuminate\Support\Facades\Log::error("Error saving setting file $key: " . $e->getMessage());
                    }
                } else {
                    \Illuminate\Support\Facades\Log::warning("File $key is invalid.");
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

