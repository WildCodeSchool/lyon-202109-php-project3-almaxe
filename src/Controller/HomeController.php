<?php

namespace App\Controller;

use App\Form\SearchProductType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
            $keyWords = strval($keyWords);
            $keyWords = implode('%20', explode(' ', $keyWords));
            // redirect to result page giving keyWord as GET paramaters
            return $this->redirectToRoute('product_search', ['keyWords' => $keyWords]);
        }

        return $this->render('home/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
