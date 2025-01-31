<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\SeoUrlCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Seo extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'title',
        'meta',
        'keywords',
        'description'
    ];

    protected $casts = [
        'title' => SeoUrlCast::class
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::created(function (Seo $model){
            Cache::forget('seo_' . str($model->url)->slug('_'));
        });

        static::updated(function (Seo $model){
            Cache::forget('seo_' . str($model->url)->slug('_'));
        });

        static::deleted(function (Seo $model){
            Cache::forget('seo_' . str($model->url)->slug('_'));
        });
    }
}
