<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mariage")
 */
class WeddingController extends AbstractController
{
    /**
     * @Route("/", name="wedding")
     */
    public function index(): Response
    {
        return $this->render('wedding/index.html.twig', [
            'controller_name' => 'WeddingController',
        ]);
    }
}
