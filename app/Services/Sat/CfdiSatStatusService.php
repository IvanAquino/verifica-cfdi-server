<?php

namespace App\Services\Sat;

use CfdiUtils\ConsultaCfdiSat\RequestParameters;
use CfdiUtils\ConsultaCfdiSat\StatusResponse;
use CfdiUtils\ConsultaCfdiSat\WebService;

class CfdiSatStatusService
{
    private WebService $webService;

    public function __construct()
    {
        $this->webService = new WebService;
    }

    public function validate(
        string $version,
        string $uuid,
        string $issuerRfc,
        string $receiverRfc,
        string $total,
        string $seal,
    ): StatusResponse {
        $params = new RequestParameters(
            version: $version,
            rfcEmisor: $issuerRfc,
            rfcReceptor: $receiverRfc,
            total: $total,
            uuid: $uuid,
            sello: $seal,
        );

        return $this->webService->request($params);
    }
}
