<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\VerifyCfdiRequest;
use App\Models\SatValidation;
use App\Services\Sat\CfdiSatStatusService;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Support\Uri;

#[Group('Verificación de CFDI')]
class VerifyCfdiController extends Controller
{
    /**
     * Verifica el estado de un CFDI
     */
    public function __invoke(VerifyCfdiRequest $request)
    {
        $version = '4.0';

        $urlParams = Uri::of($request->qr)->query()->all();

        $validationResult = app(CfdiSatStatusService::class)->validate(
            version: $version,
            uuid: $urlParams['id'],
            issuerRfc: $urlParams['re'],
            receiverRfc: $urlParams['rr'],
            total: $urlParams['tt'],
            seal: $urlParams['fe'],
        );

        $cancellationStatus = trim($validationResult->getCancellationStatus());

        if ($cancellationStatus === '') {
            $cancellationStatus = null;
        }

        SatValidation::updateOrCreate(
            [
                'uuid' => $urlParams['id'],
            ],
            [
                'issuer_rfc' => $urlParams['re'] ?? null,
                'receiver_rfc' => $urlParams['rr'] ?? null,
                'total' => $urlParams['tt'] ?? null,
                'seal' => $urlParams['fe'] ?? null,
                'version' => $version,
                'response_code' => $validationResult->getCode(),
                'cfdi_status' => $validationResult->getCfdi(),
                'cancellable_status' => $validationResult->getCancellable(),
                'cancellation_status' => $cancellationStatus,
                'efos_validation' => $validationResult->getValidationEfos(),
            ],
        );

        $data = [
            'code' => $validationResult->getCode(),
            'status' => $validationResult->getCfdi(),
            'cancellable' => $validationResult->getCancellable(),
            'cancelation_status' => $validationResult->getCancellationStatus(),
            'efos_validation' => $validationResult->getValidationEfos(),
        ];

        return response()->json($data);
    }
}
