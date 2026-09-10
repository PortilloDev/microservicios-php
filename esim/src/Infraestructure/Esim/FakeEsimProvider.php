<?php

namespace App\Infraestructure\Esim;

use App\Infraestructure\Esim\Dto\EsimProvisionRequest;
use App\Infraestructure\Esim\Dto\EsimProvisionResult;

/**
 * Simulated eSIM operator / SM-DP+ provider.
 *
 * Generates realistic identifiers (ICCID, IMSI, MSISDN) and a standard
 * LPA activation code (LPA:1$<SM-DP+ address>$<matching id>).
 */
class FakeEsimProvider implements EsimProviderInterface
{
    private const PROVIDER_NAME = 'fake';

    private const PROFILES = [
        'default' => [
            'name' => 'eSIM Global 5GB',
            'data' => '5GB',
            'validity' => '30 days',
        ],
        'global_1gb' => [
            'name' => 'eSIM Global 1GB',
            'data' => '1GB',
            'validity' => '7 days',
        ],
        'europe_10gb' => [
            'name' => 'eSIM Europe 10GB',
            'data' => '10GB',
            'validity' => '30 days',
        ],
    ];

    public function getName(): string
    {
        return self::PROVIDER_NAME;
    }

    public function provision(EsimProvisionRequest $request): EsimProvisionResult
    {
        $profile = self::PROFILES[$request->plan] ?? self::PROFILES['default'];

        $smdpAddress = 'smdp.fake-esim.io';
        $matchingId = strtoupper(bin2hex(random_bytes(16)));
        $activationCode = sprintf('LPA:1$%s$%s', $smdpAddress, $matchingId);

        return new EsimProvisionResult(
            iccid: '8944'.$this->randomDigits(15),
            imsi: '214'.$this->randomDigits(12),
            msisdn: '+34600'.$this->randomDigits(6),
            smdpAddress: $smdpAddress,
            matchingId: $matchingId,
            activationCode: $activationCode,
            qrCodeUrl: 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data='.rawurlencode($activationCode),
            profileName: $profile['name'],
            dataAllowance: $profile['data'],
            validity: $profile['validity'],
        );
    }

    private function randomDigits(int $length): string
    {
        $digits = '';
        for ($i = 0; $i < $length; ++$i) {
            $digits .= (string) random_int(0, 9);
        }

        return $digits;
    }
}
