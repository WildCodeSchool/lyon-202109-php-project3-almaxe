<?php

namespace App\Controller;

use App\Service\FromAmazon;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AmazonController extends AbstractController
{
    /**
     * @Route("/amazon", name="amazon")
     */
    public function index(): Response
    {
        return $this->render('amazon/index.html.twig', [
            'controller_name' => 'AmazonController',
        ]);
    }
}
