<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SslCertificate>
 */
class SslCertificateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'domain_id' => \App\Models\Domain::factory(),
            'port' => 443,
            'status' => $this->faker->randomElement([
                \App\Models\Enum\SslStatus::VALID->value,
                \App\Models\Enum\SslStatus::EXPIRING->value,
                \App\Models\Enum\SslStatus::EXPIRED->value
            ]),
            'expired' => $this->faker->dateTimeBetween('-1 year', '+1 year'),
        ];
    }
}
