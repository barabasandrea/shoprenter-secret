<?php

namespace App\Service;

use App\Entity\Secret;
use App\Repository\SecretRepository;
class SecretService
{
    private SecretRepository $secretRepository;

    public function __construct(SecretRepository $secretRepository)
    {
        $this->secretRepository = $secretRepository;
    }

    public function findOneActiveSecretByHash(string $hash): ?Secret
    {
        return $this->secretRepository->findOneActiveSecretByHash($hash);
    }

    public function findBySecretText(string $secretText): ?Secret
    {
        return $this->secretRepository->findBySecretText($secretText);
    }
}