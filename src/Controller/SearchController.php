<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Service\ObjectSerializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/search", name="search_")
 */
class SearchController extends AbstractController
{
    /**
     * @Route("/dimensions/{height}/{width}/{depth}", name="dimensions", defaults={"_format"="json"})
     */
    public function searchHeight(int $height, int $width, int $depth, ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAllLesserThanDimensions($height, $width, $depth);

        return $this->render('search/search.json.twig', [
            'products' => $products,
        ]);
    }
}