<?php

namespace App\Controller;

use Exception;
use App\Entity\Category;
use App\Form\SearchProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request as BrowserKitRequest;
use Symfony\Component\HttpFoundation\Session\Session;

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
                    'request' => $request,

                ],
                307
            );
        }

        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/search", name="product_search", methods={"GET", "POST"})
     */
    public function searchFromProductPage(
        Request $request,
        ProductRepository $productRepository,
        PaginatorInterface $paginator,
        Session $session
    ): Response {
        $form = $this->createForm(SearchProductType::class);

        if (!empty($request->request->all())) {
            $session->set('last_request', $request->request->all());
        };

        $search = $form->handleRequest($request);
        $articles = [];
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
                'price' => $price
            ];

            $articles = $productRepository->searchProduct($searchParameters);
        } else {
            if (is_array($session->get('last_request'))) {
                $searchParamPage = $session->get('last_request')['search_product'];

                if (is_array($searchParamPage)) {
                    $searchParametersPage = [
                        'category' => $searchParamPage['category'],
                        'minWidth' => intval($searchParamPage['minWidth']),
                        'minDepth' => intval($searchParamPage['minDepth']),
                        'minHeight' => intval($searchParamPage['minHeight']),
                        'maxWidth' => intval($searchParamPage['maxWidth']),
                        'maxDepth' => intval($searchParamPage['maxDepth']),
                        'maxHeight' => intval($searchParamPage['maxHeight']),
                        'price' => intval($searchParamPage['price'])
                    ];


                    $articles = $productRepository->searchProduct($searchParametersPage);
                }
            }
        }

        $products = $paginator->paginate(
            $articles, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            20 // Nombre de résultats par page
        );

        return $this->render('home/search.html.twig', [
            'form' => $form->createView(),
            'products' => $products,
            'request' => $request,

        ]);
    }
}
