<?php

use App\Rules\CfdiVerificationUrl;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

uses(TestCase::class);

it('accepts a valid SAT verification URL', function () {
    $data = [
        'cfdi' => 'https://verificacfdi.facturaelectronica.sat.gob.mx/?id=764E720C-0309-478A-AF51-D5E3E002CBF7&re=JUAF930105A72&rr=SME2201106T8&tt=12490.00&fe=p6UgDw==',
    ];

    $validator = Validator::make($data, [
        'cfdi' => [new CfdiVerificationUrl],
    ]);

    expect($validator->passes())->toBeTrue();
});

it('rejects URLs without the required parameters', function () {
    $data = [
        'cfdi' => 'https://verificacfdi.facturaelectronica.sat.gob.mx/?id=764E720C-0309-478A-AF51-D5E3E002CBF7&re=JUAF930105A72&rr=SME2201106T8&fe=p6UgDw==',
    ];

    $validator = Validator::make($data, [
        'cfdi' => [new CfdiVerificationUrl],
    ]);

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->first('cfdi'))->toBe('La URL de verificación de CFDI no es válida.');
});

it('rejects URLs from a different host', function () {
    $data = [
        'cfdi' => 'https://example.com/?id=764E720C-0309-478A-AF51-D5E3E002CBF7&re=JUAF930105A72&rr=SME2201106T8&tt=12490.00&fe=p6UgDw==',
    ];

    $validator = Validator::make($data, [
        'cfdi' => [new CfdiVerificationUrl],
    ]);

    expect($validator->fails())->toBeTrue();
});
