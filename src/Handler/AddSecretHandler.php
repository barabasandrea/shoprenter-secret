<?php

namespace App\Handler;

use App\Entity\Secret;
use App\Request\AddSecretRequest;
use App\Response\SecretResponse;
use Doctrine\ORM\EntityManagerInterface;

class AddSecretHandler extends AbstractSecretHandler
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function handle(AddSecretRequest $request): SecretResponse
    {
        $secret = $this->createNewSecret($request);
        return $this->createSecretResponse($secret);
    }


    public function createNewSecret(AddSecretRequest $request): Secret
    {
        $secret = new Secret();
        $secret->setSecretText($request->getSecret());
        $secret->setHash($request->getSecret());
        $secret->setCreatedAt(new \DateTime());
        $secret->setRemainingViews($request->getExpireAfterViews());
        $secret->setExpiresAt($request->getExpireAfter() > 0 ? (new \DateTime())->modify('+ ' . $request->getExpireAfter() . ' minutes') : null);

        $this->entityManager->persist($secret);
        $this->entityManager->flush();

        return $secret;
    }
}