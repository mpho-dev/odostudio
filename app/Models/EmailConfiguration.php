<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailConfiguration extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'value' => 'encrypted',
        ];
    }

    public static function getConfig($key, $default = null)
    {
        $config = self::where('key', $key)->first();

        return $config ? $config->value : $default;
    }

    public static function setConfig($key, $value)
    {
        return self::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
