<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;

class CfdiVerificationUrl implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            $fail($this->message());

            return;
        }

        if (! filter_var($value, FILTER_VALIDATE_URL)) {
            $fail($this->message());

            return;
        }

        $components = parse_url($value);

        if (! $this->hasValidStructure($components)) {
            $fail($this->message());

            return;
        }

        parse_str($components['query'] ?? '', $parameters);

        if (! $this->hasRequiredParameters($parameters)) {
            $fail($this->message());

            return;
        }

        if (! $this->parametersAreValid($parameters)) {
            $fail($this->message());
        }
    }

    /**
     * @param  array<string, mixed>  $components
     */
    private function hasValidStructure(array $components): bool
    {
        $scheme = $components['scheme'] ?? null;
        $host = $components['host'] ?? null;

        if ($scheme !== 'https') {
            return false;
        }

        if ($host !== 'verificacfdi.facturaelectronica.sat.gob.mx') {
            return false;
        }

        return true;
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    private function hasRequiredParameters(array $parameters): bool
    {
        $required = ['id', 're', 'rr', 'tt', 'fe'];

        foreach ($required as $key) {
            if (! array_key_exists($key, $parameters) || $parameters[$key] === '') {
                return false;
            }
        }

        return true;
    }

    /**
     * @param  array<string, string>  $parameters
     */
    private function parametersAreValid(array $parameters): bool
    {
        return $this->idIsValid($parameters['id'])
            && $this->rfcIsValid($parameters['re'])
            && $this->rfcIsValid($parameters['rr'])
            && $this->totalIsValid($parameters['tt'])
            && $this->sealIsValid($parameters['fe']);
    }

    private function idIsValid(string $id): bool
    {
        return (bool) preg_match('/^[A-F0-9]{8}-[A-F0-9]{4}-[A-F0-9]{4}-[A-F0-9]{4}-[A-F0-9]{12}$/', $id);
    }

    private function rfcIsValid(string $rfc): bool
    {
        $uppercase = Str::upper($rfc);

        if ($uppercase !== $rfc) {
            return false;
        }

        return (bool) preg_match('/^[A-Z&Ñ]{3,4}[0-9]{6}[A-Z0-9]{2,3}$/', $rfc);
    }

    private function totalIsValid(string $total): bool
    {
        return (bool) preg_match('/^\d{1,16}(\.\d{1,6})?$/', $total);
    }

    private function sealIsValid(string $seal): bool
    {
        return (bool) preg_match('/^[A-Za-z0-9+\/]{4,}={0,2}$/', $seal);
    }

    private function message(): string
    {
        return 'La URL de verificación de CFDI no es válida.';
    }
}
