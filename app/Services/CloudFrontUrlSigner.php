<?php

namespace App\Services;

use Aws\CloudFront\CloudFrontClient;

class CloudFrontUrlSigner
{
    protected $cloudFrontClient;
    protected $keyPairId;
    protected $privateKey;
    protected $domain;
    protected $expiration;

    public function __construct()
    {
        $this->keyPairId = config('services.cloudfront.key_pair_id');
        $this->privateKey = file_get_contents(config('services.cloudfront.private_key_path'));
        $this->domain = config('services.cloudfront.domain');
        $this->expiration = config('services.cloudfront.url_expiration', 3600);

        $this->cloudFrontClient = new CloudFrontClient([
            'version' => 'latest',
            'region' => config('filesystems.disks.s3.region'),
        ]);
    }

    public function getSignedUrl(string $videoPath): string
    {
        $resourceKey = 'https://' . $this->domain . '/' . ltrim($videoPath, '/');

        $expires = time() + $this->expiration; // Expire dans X secondes

        $signedUrl = $this->cloudFrontClient->getSignedUrl([
            'url' => $resourceKey,
            'expires' => $expires,
            'private_key' => $this->privateKey,
            'key_pair_id' => $this->keyPairId
        ]);

        return $signedUrl;
    }

    public function getSignedCookie(): array
    {
        // Pour signer plusieurs vidéos avec un seul cookie
        $policy = $this->createCustomPolicy();
        $expires = time() + $this->expiration;
        return $this->cloudFrontClient->getSignedCookie([
            'policy' => $policy,
            'private_key' => $this->privateKey,
            'key_pair_id' => $this->keyPairId
        ]);
    }

    protected function createCustomPolicy(): string
    {
        $expires = time() + $this->expiration;

        // Si vous voulez autoriser tous les fichiers sous /sign/
        $resource = 'https://' . $this->domain . '/*';

        // OU si vous voulez être spécifique
        // $resource = 'https://' . $this->domain . '/' . ltrim($paths[0], '/');

        $policy = [
            'Statement' => [
                [
                    'Resource' => $resource,
                    'Condition' => [
                        'DateLessThan' => [
                            'AWS:EpochTime' => $expires
                        ]
                    ]
                ]
            ]
        ];

        return json_encode($policy, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }


    
}
