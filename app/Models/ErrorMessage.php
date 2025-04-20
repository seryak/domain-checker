<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ErrorMessage extends Model
{
    protected $fillable = [
        'text',
        'metadata',
        'errorable_id',
        'errorable_type'
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    protected $attributes = [
        'metadata' => '{}',
    ];

    public function errorable(): MorphTo
    {
        return $this->morphTo();
    }
}