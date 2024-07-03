<?php

namespace App\Handler;

use App\Entity\Secret;
use App\Resources\View\SecretItem;
use App\Response\SecretResponse;

abstract class AbstractSecretHandler
{
    public function createSecretResponse(Secret $secret): SecretResponse
    {
        $response = new SecretResponse();

        $item = new SecretItem(
            $secret->getSecretText(),
            $secret->getCreatedAt(),
            $secret->getExpiresAt(),
            $secret->getRemainingViews());

        $response->setItem($item);

        return $response;
    }
}