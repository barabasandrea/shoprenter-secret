<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class IndexController extends AbstractController
{
    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('index.html.twig');
    }

    /**
     * @Route("/submit-secret", name="submit_secret", methods={"POST"})
     */
    public function submitSecret(Request $request): Response
    {
        $secret = $request->request->get('secret');
        $expireAfterViews = $request->request->get('expireAfterViews');
        $expireAfter = $request->request->get('expireAfter');

        $baseUrl = $request->getSchemeAndHttpHost();
        $url = $baseUrl . '/v1/secret';

        try {
            $response = $this->httpClient->request('POST', $url, [
                'body' => [
                    'secret' => $secret,
                    'expireAfterViews' => $expireAfterViews,
                    'expireAfter' => $expireAfter,
                ]
            ]);

            $data = $response->toArray();

            return $this->render('index.html.twig', [
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return $this->render('index.html.twig', [
                'error_message' => 'Failed to submit secret. Please try again later.',
            ]);
        }
    }


    /**
     * @Route("/get-secret/{hash}", name="get_secret_by_hash", methods={"GET"})
     */
    public function getSecretByHash(Request $request, string $hash): Response
    {
        $baseUrl = $request->getSchemeAndHttpHost();
        $url = sprintf('%s/v1/secret/%s', $baseUrl, $hash);

        try {
            $response = $this->httpClient->request('GET', $url);

            $statusCode = $response->getStatusCode();
            if ($statusCode === 200) {
                $data = $response->toArray();
                $secretText = $data['secretText'];

                return $this->render('secret_detail.html.twig', [
                    'secret' => $secretText,
                ]);
            } else {
                return new Response('Error by server.', $statusCode);
            }
        } catch (\Throwable $e) {
            return new Response('Error by API request: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
