<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();

        return $setting ? $setting->value : $default;
    }

    public static function set($key, $value)
    {
        return self::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    /**
     * Determine if the Home button should be displayed.
     * Treats the stored value as a boolean (supports 1/0, true/false, yes/no, etc.).
     */
    public static function isHomeButtonEnabled(): bool
    {
        $value = self::get('home_button_enabled', true);

        return (bool) filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
}
