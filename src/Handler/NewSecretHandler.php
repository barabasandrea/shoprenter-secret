<?php

namespace App\Handler;

use App\Entity\Secret;
use App\Request\NewSecretRequest;
use App\Response\SecretResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class NewSecretHandler extends AbstractSecretHandler
{
    private EntityManagerInterface $entityManager;

    public function __construct(UrlGeneratorInterface $urlGenerator, EntityManagerInterface $entityManager)
    {
        parent::__construct($urlGenerator);
        $this->entityManager = $entityManager;
    }

    public function handle(NewSecretRequest $request): SecretResponse
    {
        $secret = $this->createNewSecret($request);
        return $this->createSecretResponse($secret);
    }


    public function createNewSecret(NewSecretRequest $request): Secret
    {
        $secret = new Secret();
        $secret->setSecretText($request->getSecret());
        $secret->setHash(md5($request->getSecret()));
        $secret->setCreatedAt(new \DateTime());
        $secret->setRemainingViews($request->getExpireAfterViews());
        $secret->setExpiresAt($request->getExpireAfter() > 0 ? (new \DateTime())->modify('+ ' . $request->getExpireAfter() . ' minutes') : null);

        $this->entityManager->persist($secret);
        $this->entityManager->flush();

        return $secret;
    }
}