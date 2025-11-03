<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatValidation extends Model
{
    /** @use HasFactory<\Database\Factories\SatValidationFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'version',
        'uuid',
        'issuer_rfc',
        'receiver_rfc',
        'total',
        'seal',
        'response_code',
        'cfdi_status',
        'cancellable_status',
        'cancellation_status',
        'efos_validation',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
