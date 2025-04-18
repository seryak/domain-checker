<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    /** @use HasFactory<\Database\Factories\DomainFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'expired_at',
        'status'
    ];

    protected $casts = [
        'expired_at' => 'datetime',
    ];

    public function sslCertificates(): Domain|\Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SslCertificate::class);
    }
}
