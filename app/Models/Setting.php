<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'group', 'label', 'description'];

    protected $casts = [
        'value' => 'json',
    ];

    public static function getValue(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        if (!$setting) {
            return $default;
        }

        return $setting->castValue();
    }

    public static function setValue(string $key, $value, string $type = 'string', ?string $group = null, ?string $label = null, ?string $description = null): self
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'type' => $type, 'group' => $group, 'label' => $label, 'description' => $description]
        );
    }

    protected function castValue()
    {
        return match ($this->type) {
            'integer' => (int) $this->value,
            'float' => (float) $this->value,
            'boolean' => (bool) $this->value,
            'json' => is_string($this->value) ? json_decode($this->value, true) : $this->value,
            default => $this->value,
        };
    }

    public static function getGroup(string $group): array
    {
        return static::where('group', $group)->get()->mapWithKeys(function ($setting) use ($group) {
            $shortKey = str_replace($group . '.', '', $setting->key);
            return [$shortKey => $setting->castValue()];
        })->toArray();
    }
}
