<?php

namespace App\Models;

use Database\Factories\SslCertificateFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SslCertificate extends Model
{
    /** @use HasFactory<SslCertificateFactory> */
    use HasFactory;

    protected $fillable = [
        'domain_id',
        'port',
        'status',
        'expired',
    ];

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }

    public function errorMessages()
    {
        return $this->morphMany(ErrorMessage::class, 'errorable');
    }

    protected $casts = [
        'expired' => 'datetime',
        'port' => 'integer',
    ];
}
