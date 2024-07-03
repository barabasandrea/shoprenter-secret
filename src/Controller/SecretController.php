<?php

namespace App\Controller;

use App\Handler\NewSecretHandler;
use App\Handler\SecretHandler;
use App\Request\NewSecretRequest;
use App\Request\SecretRequest;
use App\Service\SecretService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;

class SecretController
{
    private NewSecretHandler $addSecretHandler;
    private SecretHandler $secretHandler;
    private SerializerInterface $serializer;
    private SecretService $secretService;

    public function __construct(
        NewSecretHandler    $addSecretHandler,
        SecretHandler       $secretHandler,
        SerializerInterface $serializer,
        SecretService       $secretService
    )
    {
        $this->addSecretHandler = $addSecretHandler;
        $this->secretHandler = $secretHandler;
        $this->serializer = $serializer;
        $this->secretService = $secretService;
    }


    /**
     * @Route("/secret", methods={"POST"})
     */
    public function addSecret(Request $request): Response
    {
        $addSecretRequest = new NewSecretRequest();
        $secret = $request->get('secret');

        if ($this->secretService->findOneActiveSecretByHash($secret) !== null) {
            return new JsonResponse(['message' => 'The key already exist'], Response::HTTP_CONFLICT);
        }

        $addSecretRequest->setSecret($secret);
        $addSecretRequest->setExpireAfterViews((int)$request->get('expireAfterViews'));
        $addSecretRequest->setExpireAfter((int)$request->get('expireAfter'));

        $secretResponse = $this->addSecretHandler->handle($addSecretRequest);

        $format = $this->getFormatFromRequest($request);
        $serializedContent = $this->serializer->serialize($secretResponse, $format);

        $response = new Response($serializedContent);
        $response->headers->set('Content-Type', $this->getContentTypeFromFormat($format));

        return $response;
    }

    /**
     * @Route("/secret/{hash}", methods={"GET"})
     */
    public function getSecretByHash(Request $request): Response
    {
        $secretRequest = new secretRequest();
        $secretRequest->setHash((string)$request->get('hash'));

        $secretResponse = $this->secretHandler->handle($secretRequest);

        if ($secretResponse === null) {
            return new JsonResponse(['message' => 'Secret not found'], Response::HTTP_NOT_FOUND);
        }

        $format = $this->getFormatFromRequest($request);
        $serializedContent = $this->serializer->serialize($secretResponse, $format);

        $response = new Response($serializedContent);
        $response->headers->set('Content-Type', $this->getContentTypeFromFormat($format));

        return $response;
    }

    private function getFormatFromRequest(Request $request): string
    {
        $preferredFormat = $request->getPreferredFormat();
        return $preferredFormat ?: 'json';
    }

    private function getContentTypeFromFormat(string $format): string
    {
        switch ($format) {
            case 'json':
                return 'application/json';
            case 'xml':
                return 'application/xml';
            default:
                throw new \InvalidArgumentException('Unsupported format: ' . $format);
        }
    }
}
