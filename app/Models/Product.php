<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'sku',
        'price',
        'compare_at_price',
        'price_on_request',
        'stock_quantity',
        'short_description',
        'description',
        'is_featured',
        'is_new_arrival',
        'is_active',
        'meta_title',
        'meta_description',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'compare_at_price' => 'decimal:2',
            'price_on_request' => 'boolean',
            'stock_quantity' => 'integer',
            'is_featured' => 'boolean',
            'is_new_arrival' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderByDesc('is_primary')->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getPriceLabelAttribute(): string
    {
        if ($this->price_on_request || $this->price === null) {
            return 'Price on request';
        }

        return '₹'.number_format((float) $this->price, 2);
    }

    public function getCompareAtPriceLabelAttribute(): ?string
    {
        if ($this->price_on_request || $this->price === null) {
            return null;
        }

        if ($this->compare_at_price === null || (float) $this->compare_at_price <= (float) $this->price) {
            return null;
        }

        return '₹'.number_format((float) $this->compare_at_price, 2);
    }

    public function getIsInStockAttribute(): bool
    {
        return $this->stock_quantity > 0;
    }
}
