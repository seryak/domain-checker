<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\Service\DomainNameConverter;

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

    /**
     * Set the name attribute, converting domain to punycode
     *
     * @param  string  $value
     * @return void
     * @throws \InvalidArgumentException If domain conversion fails
     */
    public function setNameAttribute($value)
    {
        try {
            $this->attributes['name'] = app(DomainNameConverter::class)->toPunycode($value);
        } catch (\Exception $e) {
            Log::error('Failed to convert domain to punycode: ' . $value . '. Error: ' . $e->getMessage());
            throw new \InvalidArgumentException('Невозможно конвертировать домен "' . $value . '" в punycode: ' . $e->getMessage());
        }
    }

    public function sslCertificates(): Domain|\Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SslCertificate::class);
    }

    public function errorMessages()
    {
        return $this->morphMany(ErrorMessage::class, 'errorable');
    }

    /**
     * Конвертирует имя домена из punycode в human-readable Unicode
     *
     * @return string
     */
    public function getNameHumanReadableAttribute(): string
    {
        try {
            return app(DomainNameConverter::class)->toUnicode($this->name);
        } catch (\Exception $e) {
            Log::error('Failed to convert domain from punycode to Unicode: ' . $this->name . '. Error: ' . $e->getMessage());
            return $this->name; // Возвращаем оригинальное значение при ошибке
        }
    }
}
