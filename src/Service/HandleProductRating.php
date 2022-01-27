<?php

namespace App\Service;

use Exception;
use App\Entity\Review;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class HandleProductRating
{
    private EntityManagerInterface $entityManager;
    private const NB_REVIEWS_MIN = 1;
    private const NB_REVIEWS_MAX = 5;

    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    public function addRating(array $products): void
    {
        foreach ($products as $product) {
            if (!$product instanceof Product) {
                throw new Exception('Product is not instance of Product entity');
            }

            if (count($product->getReviews()) > 0) {
                continue;
            }

            $nbReviews = rand(self::NB_REVIEWS_MIN, self::NB_REVIEWS_MAX);

            for ($i = 0; $i < $nbReviews; $i++) {
                $review = new Review();
                $this->setRandomReview($review);

                $review->setProduct($product);

                $this->entityManager->persist($review);
            }
        }

        $this->entityManager->flush();
    }

    private function setRandomReview(Review $review): void
    {
        $fornames = [
            'John', 'Valentin', 'Matthieu', 'Jérémy', 'Manuela',
            'Jean-Christophe', 'Anthony', 'FX', 'Falilou', 'Sandrine', 'Michel',
            'Loïc', 'Thomas'
        ];
        $lastnames = ['Martin', 'Durand', 'Dupont'];
        $ratings = [
            [1, 'Pas confortable'],
            [2, 'Notice pas claire'],
            [3, "Joli meuble mais le rendu n'est pas conforme à la photo du site"],
            [4, 'Super confortable et dimensions au poil !'],
            [5, 'Tip-top, pile-poil !!']
        ];

        $randIndexForname = array_rand($fornames);
        $randIndexLastname = array_rand($lastnames);
        $randIndexRating = array_rand($ratings);

        $review->setReviewerForname($fornames[$randIndexForname]);
        $review->setReviewerLastname($lastnames[$randIndexLastname]);
        $review->setRating($ratings[$randIndexRating][0]);
        $review->setComment($ratings[$randIndexRating][1]);
    }
}
