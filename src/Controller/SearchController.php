<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/search", name="search_")
 */
class HomeController extends AbstractController
{

    
    /**
     * @Route("/", name="index")
     */
    public function index(): array
    {
        return
    }
}
