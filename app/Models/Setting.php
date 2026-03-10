<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value', 'type'];

    /**
     * Get setting value by key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function getValue($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        return $setting->value;
    }

    public static function getImageUrl($key, $default = null)
    {
        $value = self::getValue($key, null);

        if (!$value) {
            return $default;
        }

        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        $cleanPath = ltrim($value, '/');

        if (str_starts_with($cleanPath, 'uploads/') || str_starts_with($cleanPath, 'storage/')) {
            return asset($cleanPath);
        }

        return asset('uploads/' . $cleanPath);
    }
}
