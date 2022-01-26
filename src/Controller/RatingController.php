<?php

namespace App\Controller;

use App\Form\RatingType;
use App\Repository\ProductRepository;
use App\Service\HandleProductRating;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RatingController extends AbstractController
{
    /**
     * @Route("/admin/rating", name="rating")
     */
    public function index(
        Request $request,
        ProductRepository $productRepository,
        HandleProductRating $ratingHandler
    ): Response {
        $form = $this->createForm(RatingType::class);
        $checkbox = $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $confirm = $checkbox->get('confirm')->getData();

            if ($confirm) {
                $products = $productRepository->findAll();
                $ratingHandler->addRating($products);

                return $this->render('rating/index.html.twig', [
                    'form' => $form->createView(),
                    'status' => 'Ratings added',
                ]);
            }
        }

        return $this->render('rating/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
