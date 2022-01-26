<?php

namespace App\Controller;

use App\Form\RatingType;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RatingController extends AbstractController
{
    /**
     * @Route("/db/rating", name="rating")
     */
    public function index(Request $request, ProductRepository $productRepository): Response
    {
        $form = $this->createForm(RatingType::class);
        $checkbox = $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $confirm = $checkbox->get('confirm')->getData();

            if ($confirm) {
                $products = $productRepository->findAll();
                var_dump($products);
            }
        }

        return $this->render('rating/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
