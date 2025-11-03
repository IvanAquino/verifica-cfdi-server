<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SatValidation>
 */
class SatValidationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cfdiStatuses = ['Vigente', 'Cancelado', 'No Encontrado'];
        $cancellableStatuses = [
            'No cancelable',
            'Cancelable sin aceptación',
            'Cancelable con aceptación',
        ];
        $cancellationStatuses = [
            '(ninguno)',
            'Cancelado sin aceptación',
            'Cancelado con aceptación',
            'En proceso',
            'Plazo vencido',
            'Solicitud rechazada',
        ];

        return [
            'version' => '4.0',
            'uuid' => Str::upper(Str::uuid()->toString()),
            'issuer_rfc' => strtoupper(fake()->bothify('???######?#?#')),
            'receiver_rfc' => strtoupper(fake()->bothify('???######?#?#')),
            'total' => number_format(fake()->randomFloat(2, 1, 999999), 6, '.', ''),
            'seal' => strtoupper(fake()->regexify('[A-F0-9]{8}')),
            'response_code' => 'S - Comprobante obtenido satisfactoriamente',
            'cfdi_status' => fake()->randomElement($cfdiStatuses),
            'cancellable_status' => fake()->randomElement($cancellableStatuses),
            'cancellation_status' => fake()->optional()->randomElement($cancellationStatuses),
            'efos_validation' => fake()->randomElement(['100', '200']),
        ];
    }
}
