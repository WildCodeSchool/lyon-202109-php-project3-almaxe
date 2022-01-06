<?php

namespace App\Controller;

use App\Services\CrawlerMaisonDuMondeInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CrawlerController extends AbstractController
{
    /**
     * @Route("/crawler", name="crawler")
     */
    public function index(CrawlerMaisonDuMondeInterface $crawler): Response
    {
        $crawler->getData();
        return $this->render('crawler/index.html.twig', [
            'controller_name' => 'CrawlerController',
        ]);
    }
}
