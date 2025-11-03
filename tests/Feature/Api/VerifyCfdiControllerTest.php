<?php

declare(strict_types=1);

use App\Models\SatValidation;
use App\Services\Sat\CfdiSatStatusService;
use CfdiUtils\ConsultaCfdiSat\StatusResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Mockery;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;

uses(RefreshDatabase::class);

test('stores a SAT validation record', function () {
    $uuid = Str::upper(Str::uuid()->toString());
    $issuerRfc = 'AAA010101AAA';
    $receiverRfc = 'BBB010101BBB';
    $total = '12345.670000';
    $seal = Str::upper(Str::random(10));

    $qr = 'https://verificacfdi.facturaelectronica.sat.gob.mx/default.aspx?'.http_build_query([
        'id' => $uuid,
        're' => $issuerRfc,
        'rr' => $receiverRfc,
        'tt' => $total,
        'fe' => $seal,
    ]);

    $statusResponse = new StatusResponse(
        'S - Comprobante obtenido satisfactoriamente',
        'Vigente',
        'Cancelable sin aceptación',
        'Cancelado con aceptación',
        '200',
    );

    $serviceMock = Mockery::mock(CfdiSatStatusService::class);
    $serviceMock->shouldReceive('validate')
        ->once()
        ->with('4.0', $uuid, $issuerRfc, $receiverRfc, $total, $seal)
        ->andReturn($statusResponse);

    $this->app->instance(CfdiSatStatusService::class, $serviceMock);

    $response = postJson('/api/verify-cfdi', [
        'qr' => $qr,
    ]);

    $response->assertOk()->assertExactJson([
        'code' => 'S - Comprobante obtenido satisfactoriamente',
        'status' => 'Vigente',
        'cancellable' => 'Cancelable sin aceptación',
        'cancelation_status' => 'Cancelado con aceptación',
        'efos_validation' => '200',
    ]);

    assertDatabaseHas('sat_validations', [
        'uuid' => $uuid,
        'version' => '4.0',
        'issuer_rfc' => $issuerRfc,
        'receiver_rfc' => $receiverRfc,
        'total' => $total,
        'seal' => $seal,
        'response_code' => 'S - Comprobante obtenido satisfactoriamente',
        'cfdi_status' => 'Vigente',
        'cancellable_status' => 'Cancelable sin aceptación',
        'cancellation_status' => 'Cancelado con aceptación',
        'efos_validation' => '200',
    ]);

    expect(SatValidation::query()->count())->toBe(1);
});

test('updates existing SAT validation record when request parameters match', function () {
    $uuid = Str::upper(Str::uuid()->toString());
    $issuerRfc = 'CCC010101CCC';
    $receiverRfc = 'DDD010101DDD';
    $total = '789.000000';
    $seal = Str::random(12);

    $existing = SatValidation::factory()->create([
        'version' => '4.0',
        'uuid' => $uuid,
        'issuer_rfc' => $issuerRfc,
        'receiver_rfc' => $receiverRfc,
        'total' => $total,
        'seal' => $seal,
        'response_code' => 'S - Comprobante obtenido satisfactoriamente',
        'cfdi_status' => 'Vigente',
        'cancellable_status' => 'No cancelable',
        'cancellation_status' => null,
        'efos_validation' => '100',
    ]);

    $qr = 'https://verificacfdi.facturaelectronica.sat.gob.mx/default.aspx?'.http_build_query([
        'id' => $uuid,
        're' => $issuerRfc,
        'rr' => $receiverRfc,
        'tt' => $total,
        'fe' => $seal,
    ]);

    $statusResponse = new StatusResponse(
        'S - Comprobante obtenido satisfactoriamente',
        'Cancelado',
        'Cancelable con aceptación',
        '',
        '100',
    );

    $serviceMock = Mockery::mock(CfdiSatStatusService::class);
    $serviceMock->shouldReceive('validate')
        ->once()
        ->with('4.0', $uuid, $issuerRfc, $receiverRfc, $total, $seal)
        ->andReturn($statusResponse);

    $this->app->instance(CfdiSatStatusService::class, $serviceMock);

    $response = postJson('/api/verify-cfdi', [
        'qr' => $qr,
    ]);

    $response->assertOk()->assertExactJson([
        'code' => 'S - Comprobante obtenido satisfactoriamente',
        'status' => 'Cancelado',
        'cancellable' => 'Cancelable con aceptación',
        'cancelation_status' => '',
        'efos_validation' => '100',
    ]);

    $existing->refresh();

    expect(SatValidation::query()->count())->toBe(1)
        ->and($existing->cancellable_status)->toBe('Cancelable con aceptación')
        ->and($existing->cancellation_status)->toBeNull()
        ->and($existing->cfdi_status)->toBe('Cancelado');
});

afterEach(fn () => Mockery::close());
