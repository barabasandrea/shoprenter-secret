<?php

namespace App\Handler;

use App\Entity\Secret;
use App\Resources\View\SecretItem;
use App\Response\SecretResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

abstract class AbstractSecretHandler
{
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }
    public function createSecretResponse(Secret $secret): SecretResponse
    {
        $response = new SecretResponse();

        $item = new SecretItem(
            $secret->getSecretText(),
            $secret->getCreatedAt(),
            $secret->getExpiresAt(),
            $secret->getRemainingViews(),
            $this->urlGenerator->generate('secret_by_hash', ['hash' => $secret->getHash()])
        );


        $response->setItem($item);

        return $response;
    }
}