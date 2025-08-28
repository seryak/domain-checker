<?php

namespace App\Service;

use Illuminate\Support\Str;
use TrueBV\Punycode;

class DomainNameConverter
{
    protected Punycode $punycode;

    public function __construct()
    {
        $this->punycode = new Punycode();
    }

    /**
     * Конвертирует домен в punycode для внутренних операций
     *
     * @param string $domain
     * @return string
     */
    public function toPunycode(string $domain): string
    {
        // Нормализуем домен: приводим к нижнему регистру и убираем пробелы
        $domain = strtolower(trim($domain));
        
        // Убираем протоколы если есть
        $domain = preg_replace('/^https?:\/\//', '', $domain);
        
        // Убираем слэши в конце
        $domain = rtrim($domain, '/');
        
        // Конвертируем в punycode
        return $this->punycode->encode($domain);
    }

    /**
     * Конвертирует punycode обратно в Unicode (кириллицу)
     *
     * @param string $domain
     * @return string
     */
    public function toUnicode(string $domain): string
    {
        return $this->punycode->decode($domain);
    }

    /**
     * Проверяет, является ли домен валидным
     *
     * @param string $domain
     * @return bool
     */
    public function isValidDomain(string $domain): bool
    {
        // Нормализуем домен
        $domain = strtolower(trim($domain));
        
        // Убираем протоколы если есть
        $domain = preg_replace('/^https?:\/\//', '', $domain);
        
        // Убираем слэши в конце
        $domain = rtrim($domain, '/');
        
        // Проверяем базовый формат домена
        if (empty($domain) || strlen($domain) > 253) {
            return false;
        }
        
        // Проверяем символы домена
        if (!preg_match('/^[a-z0-9а-яё.-]+$/iu', $domain)) {
            return false;
        }
        
        // Проверяем, что домен содержит точку
        if (!str_contains($domain, '.')) {
            return false;
        }
        
        // Проверяем длину каждой части домена
        $parts = explode('.', $domain);
        foreach ($parts as $part) {
            if (empty($part) || strlen($part) > 63 || $part[0] === '-' || $part[-1] === '-') {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Нормализует доменное имя для отображения
     *
     * @param string $domain
     * @return string
     */
    public function normalizeForDisplay(string $domain): string
    {
        // Убираем протоколы если есть
        $domain = preg_replace('/^https?:\/\//', '', $domain);
        
        // Убираем слэши в конце
        $domain = rtrim($domain, '/');
        
        // Приводим к нижнему регистру, но сохраняем первую букву заглавной для доменов
        $domain = strtolower($domain);
        
        return $domain;
    }

    /**
     * Проверяет, содержит ли домен кириллические символы
     *
     * @param string $domain
     * @return bool
     */
    public function containsCyrillic(string $domain): bool
    {
        return preg_match('/[а-яё]/iu', $domain) === 1;
    }

    /**
     * Получает основное доменное имя без поддоменов
     *
     * @param string $domain
     * @return string
     */
    public function getDomainName(string $domain): string
    {
        $domain = $this->normalizeForDisplay($domain);
        $parts = explode('.', $domain);
        
        // Если есть поддомены, возвращаем последние две части (domain.tld)
        if (count($parts) > 2) {
            return implode('.', array_slice($parts, -2));
        }
        
        return $domain;
    }
}