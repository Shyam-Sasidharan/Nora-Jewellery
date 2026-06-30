<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteContent extends Model
{
    protected $fillable = [
        'key',
        'title',
        'content',
        'data',
        'image_path',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'array',
        ];
    }

    public static function byKey(string $key): ?self
    {
        return static::where('key', $key)->first();
    }
}
