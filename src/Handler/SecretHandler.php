<?php

namespace App\Handler;

use App\Entity\Secret;
use App\Request\SecretRequest;
use App\Response\SecretResponse;
use App\Service\SecretService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SecretHandler extends AbstractSecretHandler
{
    private SecretService $secretService;
    private EntityManagerInterface $entityManager;

    public function __construct(UrlGeneratorInterface $urlGenerator, SecretService $secretService, EntityManagerInterface $entityManager)
    {
        parent::__construct($urlGenerator);
        $this->secretService = $secretService;
        $this->entityManager = $entityManager;
    }

    public function handle(SecretRequest $request): ?SecretResponse
    {
        $secret = $this->secretService->findOneActiveSecretByHash($request->getHash());

        if (null === $secret) {
            return null;
        }

        $this->updateRemainingViews($secret);
        return $this->createSecretResponse($secret);
    }


    public function updateRemainingViews(Secret $secret): void
    {
        $secret->setRemainingViews($secret->getRemainingViews() - 1);
        $this->entityManager->persist($secret);
        $this->entityManager->flush();
    }

}