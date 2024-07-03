<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('index.html.twig');
    }

    /**
     * @Route("/secret/{hash}", name="secret_by_hash", methods={"GET"})
     */
    public function getSecretByHash(string $hash): Response
    {
        return $this->render('secret_detail.html.twig');
    }
}
