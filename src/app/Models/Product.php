<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'condition',
        'stock',
        'category_id',
        'user_id',
        'buyer_id',
        'is_sold',
        'brand',
        'categories',
        'comments',
        'comment_data',
        'image_url',   // 既存Seeder用（public/images/...）
        'image_path',  // ユーザーアップロード用（storage/app/public 下の相対）
    ];

    protected $casts = [
        'categories' => 'array',
        'comment_data' => 'array',
        'is_sold' => 'boolean',
    ];

    protected $appends = ['image_src']; // JSONでも見えるように（任意）

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function favorites()
    {
        return $this->hasMany(\App\Models\Favorite::class);
    }

    // ★表示専用URL（これだけ使えばOK）
    public function getImageSrcAttribute(): string
    {
        $resolve = function (string $value): ?string {
            $value = ltrim($value, '/');

            if (Str::startsWith($value, ['http://', 'https://'])) {
                return $value;
            }

            if (Str::startsWith($value, 'storage/')) {
                return asset($value);
            }

            if (Str::startsWith($value, 'public/')) {
                $value = ltrim(Str::after($value, 'public/'), '/');
            }

            if (Storage::disk('public')->exists($value)) {
                return Storage::url($value);
            }

            $publicPath = public_path($value);

            if (file_exists($publicPath)) {
                return asset($value);
            }

            return null;
        };

        if (!empty($this->image_path)) {
            $resolved = $resolve($this->image_path);
            if ($resolved) {
                return $resolved;
            }
        }

        if (!empty($this->image_url)) {
            $resolved = $resolve($this->image_url);
            if ($resolved) {
                return $resolved;
            }
        }

        return asset('images/noimage.svg');
    }
}
