<?php

namespace App\Controller;

use App\Entity\Secret;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;

class SecretController extends AbstractController
{
    #[Route('/v1/secret', name: 'add_secret', methods: ['POST'])]
    public function addSecret(Request $request, EntityManagerInterface $entityManager): Response
    {
        $secretText = $request->request->get('secret');
        $expireAfterViews = $request->request->get('expireAfterViews');
        $expireAfter = $request->request->get('expireAfter');

        if (!$secretText || !$expireAfterViews || $expireAfterViews <= 0) {
            return $this->json(['error' => 'Invalid input'], Response::HTTP_BAD_REQUEST);
        }

        $secret = new Secret();
        $secret->setHash(Uuid::v4());
        $secret->setSecretText($secretText);
        $secret->setCreatedAt(new \DateTime());
        $secret->setRemainingViews($expireAfterViews);

        if ($expireAfter > 0) {
            $secret->setExpiresAt((new \DateTime())->modify("+$expireAfter minutes"));
        }

        $entityManager->persist($secret);
        $entityManager->flush();

        return $this->json([
            'hash' => $secret->getHash(),
            'secretText' => $secret->getSecretText(),
            'createdAt' => $secret->getCreatedAt(),
            'expiresAt' => $secret->getExpiresAt(),
            'remainingViews' => $secret->getRemainingViews()
        ]);
    }

    /**
     * @OA\Get(
     *     path="/v1/secret/{hash}",
     *     summary="Find a secret by hash",
     *     description="Returns a single secret based on the provided hash",
     *     @OA\Parameter(
     *         name="hash",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         @OA\JsonContent(ref="#/components/schemas/Secret")
     *     ),
     *     @OA\Response(
     *         response=404,
     *     )
     * )
     * @Route("/v1/secret/{hash}", name="get_secret_by_hash", methods={"GET"})
     */
    public function getSecretByHash(string $hash, EntityManagerInterface $entityManager): Response
    {
        $secret = $entityManager->getRepository(Secret::class)->findOneBy(['hash' => $hash]);

        if (!$secret) {
            return $this->json(['error' => 'Secret not found'], Response::HTTP_NOT_FOUND);
        }

        if ($secret->getExpiresAt() && $secret->getExpiresAt() < new \DateTime()) {
            return $this->json(['error' => 'Secret has expired'], Response::HTTP_NOT_FOUND);
        }

        if ($secret->getRemainingViews() <= 0) {
            return $this->json(['error' => 'Secret has expired'], Response::HTTP_NOT_FOUND);
        }

        $secret->setRemainingViews($secret->getRemainingViews() - 1);
        $entityManager->flush();

        return $this->json([
            'hash' => $secret->getHash(),
            'secretText' => $secret->getSecretText(),
            'createdAt' => $secret->getCreatedAt(),
            'expiresAt' => $secret->getExpiresAt(),
            'remainingViews' => $secret->getRemainingViews()
        ]);
    }
}

