<?php

namespace App\Controller;

use App\Service\AlineaCrawlerManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CrawlerController extends AbstractController
{
    /**
     * @Route("/crawler", name="crawler")
     */
    public function index(AlineaCrawlerManager $crawler): Response
    {
        $crawler->main();
        return $this->render('crawler/index.html.twig', [
            'crawler' => $crawler,
        ]);
    }
}
