<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'title',
        'properties_id',
    ];

    public function propertie(): BelongsTo
    {
        return $this->belongsTo(Propertie::class);
    }
}
