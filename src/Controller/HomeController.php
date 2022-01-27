<?php

namespace App\Controller;

use Exception;
use App\Entity\Category;
use App\Form\SearchProductType;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request): Response
    {
        $form = $this->createForm(SearchProductType::class);

        $search = $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $search->get('category')->getData();
            if (!$category instanceof Category) {
                throw new Exception('Category not found');
            }

            return $this->redirectToRoute(
                'product_search',
                [
                    'request' => $request
                ],
                307
            );
        }

        return $this->render('home/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/search", name="product_search", methods={"GET", "POST"})
     */
    public function searchFromProductPage(
        Request $request,
        ProductRepository $productRepository
    ): Response {
        $form = $this->createForm(SearchProductType::class);

        $search = $form->handleRequest($request);
        $products = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $search->get('category')->getData();
            $minWidth = $search->get('minWidth')->getData();
            $minDepth = $search->get('minDepth')->getData();
            $minHeight = $search->get('minHeight')->getData();
            $maxWidth = $search->get('maxWidth')->getData();
            $maxDepth = $search->get('maxDepth')->getData();
            $maxHeight = $search->get('maxHeight')->getData();
            $price = intval($search->get('price')->getData());

            $searchParameters = [
                'category' => $category,
                'minWidth' => $minWidth,
                'minDepth' => $minDepth,
                'minHeight' => $minHeight,
                'maxWidth' => $maxWidth,
                'maxDepth' => $maxDepth,
                'maxHeight' => $maxHeight,
                'price' => $price,
                'page' => 1
            ];

            $products = $productRepository->searchProduct($searchParameters);
        }

        return $this->render('home/search.html.twig', [
            'form' => $form->createView(),
            'products' => $products,
        ]);
    }

    /**
     * @Route("/ajax/{page}/{category}/{minHeight}/{maxHeight}/{minWidth}/{maxWidth}/{minDepth}/{maxDepth}/{price}"),
     * name="ajax_request", method="POST"
     */
    public function ajaxProduct(
        int $page,
        int $category,
        int $minHeight,
        int $maxHeight,
        int $minWidth,
        int $maxWidth,
        int $minDepth,
        int $maxDepth,
        int $price,
        ProductRepository $productRepository
    ): Response {
        $searchParameters = [
            'category' => $category,
            'minWidth' => $minWidth,
            'minDepth' => $minDepth,
            'minHeight' => $minHeight,
            'maxWidth' => $maxWidth,
            'maxDepth' => $maxDepth,
            'maxHeight' => $maxHeight,
            'price' => $price,
            'page' => $page
        ];

        $products = $productRepository->searchProduct($searchParameters);
        $productsCount = $productRepository->countSearchProduct($searchParameters);

        return new Response($this->render('home/_productsDisplay.html.twig', [
            'search' => $searchParameters,
            'productsCount' => $productsCount,
            'products' => $products,
            'page' => $page,
        ]));
    }
}
