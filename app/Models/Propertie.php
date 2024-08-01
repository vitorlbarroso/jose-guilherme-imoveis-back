<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Propertie extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'location',
        'category',
        'price',
        'is_active',
    ];

    public function medias(): HasMany
    {
        return $this->hasMany(Media::class, 'properties_id');
    }
}
