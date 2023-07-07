<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings';

    protected $fillable = [
        'key',
        'value'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var    bool
     */
    public $timestamps = false;

    public static function get_value($name)
    {
        $settings = self::where('key', $name);
        if ($settings->count() == 0) {
            throw new \Exception(__('site.emptysetting', ['setting' => $name]));
        }

        $setting = $settings->first();
        return $setting->value;
    }

    public static function update_value($name, $value)
    {
        $settings = self::where('key', $name);
        if ($settings->count() == 0) {
            throw new \Exception(__('site.emptysetting', ['setting' => $name]));
        }

        $setting = $settings->first();
        $setting->value = $value;
        $setting->save();
    }
}
