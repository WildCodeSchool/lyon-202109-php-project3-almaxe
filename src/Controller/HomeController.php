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
}
