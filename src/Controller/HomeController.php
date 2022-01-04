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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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
            $categoryName = $category->getName();
            $criteria = $search->get('criteria')->getData();
            $width = intval($search->get('width')->getData());
            $height = intval($search->get('height')->getData());
            $depth = intval($search->get('depth')->getData());
            // redirect to result page giving keyWord as GET paramaters
            return $this->redirectToRoute(
                'product_search_get',
                [
                    'category' => $categoryName,
                    'width' => $width,
                    'height' => $height,
                    'depth' => $depth,
                    'criteria' => $criteria
                ]
            );
        }

        return $this->render('home/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/search/{category}/{criteria}/{height}/{width}/{depth}", name="product_search_get", methods={"GET"})
     * @ParamConverter("category", options={"mapping": {"category": "name"}})
     */
    public function search(
        Request $request,
        ProductRepository $productRepository,
        Category $category,
        string $criteria,
        int $width,
        int $height,
        int $depth
    ): Response {
        $products = $productRepository->searchProduct(
            [
                'category' => $category,
                'width' => $width,
                'height' => $height,
                'depth' => $depth,
                'criteria' => $criteria
            ]
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
     * @Route("/search/{category}/{criteria}/{height}/{width}/{depth}", name="product_search_post", methods={"POST"})
     * @ParamConverter("category", options={"mapping": {"category": "name"}})
     */
    public function searchFromProductPage(
        Request $request,
        ProductRepository $productRepository,
        Category $category,
        string $criteria,
        int $width,
        int $height,
        int $depth
    ): Response {
        $form = $this->createForm(SearchProductType::class);

        $search = $form->handleRequest($request);
        $products = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $search->get('category')->getData();
            $criteria = $search->get('criteria')->getData();
            $width = intval($search->get('width')->getData());
            $height = intval($search->get('height')->getData());
            $depth = intval($search->get('depth')->getData());

            $products = $productRepository->searchProduct(
                [
                    'category' => $category,
                    'width' => $width,
                    'height' => $height,
                    'depth' => $depth,
                    'criteria' => $criteria
                ]
            );
        }

        return $this->render('product/search.html.twig', [
            'form' => $form->createView(),
            'products' => $products,
        ]);
    }
}
