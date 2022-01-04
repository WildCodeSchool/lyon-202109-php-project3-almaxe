<?php

namespace App\Controller;

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
            $keyWords = $search->get('keyWords')->getData();
            if ($keyWords != null) {
                $keyWords = strval($keyWords);
                $keyWords = implode('%20', explode(' ', $keyWords));
            } else {
                $keyWords = "all";
            }


            $width = intval($search->get('width')->getData());
            $height = intval($search->get('height')->getData());
            $depth = intval($search->get('depth')->getData());
            // redirect to result page giving keyWord as GET paramaters
            return $this->redirectToRoute(
                'product_search_get',
                ['keyWords' => $keyWords, 'width' => $width, 'height' => $height, 'depth' => $depth]
            );
        }

        return $this->render('home/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/search/{keyWords}/{height}/{width}/{depth}", name="product_search_get", methods={"GET"})
     */
    public function search(
        Request $request,
        ProductRepository $productRepository,
        string $keyWords = null,
        int $width,
        int $height,
        int $depth
    ): Response {
        $products = $productRepository->searchProduct(
            ['words' => $keyWords, 'width' => $width, 'height' => $height, 'depth' => $depth]
        );

        $form = $this->createForm(SearchProductType::class);
        $data = $form->getData();
        $form->setData($data);
        $form->handleRequest($request);

        return $this->render('product/search.html.twig', [
            'form' => $form->createView(),
            'products' => $products,
        ]);
    }


    /**
     * @Route("/search/{keyWords}/{height}/{width}/{depth}", name="product_search_post", methods={"POST"})
     */
    public function searchFromProductPage(
        Request $request,
        ProductRepository $productRepository,
        string $keyWords = null,
        int $width,
        int $height,
        int $depth
    ): Response {
        $form = $this->createForm(SearchProductType::class);

        $search = $form->handleRequest($request);
        $products = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $keyWords = $search->get('keyWords')->getData();
            if ($keyWords != null) {
                $keyWords = strval($keyWords);
                $keyWords = implode('%20', explode(' ', $keyWords));
            } else {
                $keyWords = "all";
            }


            $width = intval($search->get('width')->getData());
            $height = intval($search->get('height')->getData());
            $depth = intval($search->get('depth')->getData());

            $products = $productRepository->searchProduct(
                ['words' => $keyWords, 'width' => $width, 'height' => $height, 'depth' => $depth]
            );
        }

        return $this->render('product/search.html.twig', [
            'form' => $form->createView(),
            'products' => $products,
        ]);
    }
}
