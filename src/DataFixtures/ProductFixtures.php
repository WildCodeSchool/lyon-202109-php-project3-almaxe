<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public const NAME = [
        'FREDDE',
        'UPPSPEL',
        'MÅLVAKT / ALEX',
        'UTESPELARE',
        'Table Brooklyn',
        'Table à manger GEORGIA',
        'Table scandinave extensible',
        'Table à manger Rozy'
    ];

    public const PRICE = [
        249,
        479,
        109.94,
        149,
        297.80,
        94.99,
        119.99,
        49.99
    ];

    public const HEIGHT = [
        146,
        123,
        73.4,
        78,
        76.6,
        75,
        72.5,
        73
    ];

    public const WIDTH = [
        185,
        140,
        120,
        160,
        160.4,
        160,
        160,
        110
    ];

    public const DEPTH = [
        74,
        80,
        80,
        80,
        90,
        90,
        80,
        70
    ];

    public const PICTURE = [
        'https://www.ikea.com/fr/fr/images/products/fredde-bureau-gamer-noir__0736008_pe740351_s5.jpg?f=xl',
        'https://www.ikea.com/fr/fr/images/products/uppspel-bureau-gamer-noir__1039777_pe840447_s5.jpg?f=xl',
        'https://www.ikea.com/fr/fr/images/products/malvakt-alex-bureau-noir-blanc__1032042_pe836750_s5.jpg?f=xl',
        'https://www.ikea.com/fr/fr/images/products/utespelare-bureau-gamer-noir__0985179_pe816538_s5.jpg?f=xl',
        'https://media.conforama.fr/m/export/Medias/700000/40000/2000/800/10/G_742814_Y.jpg',
        'https://cdn.conforama.fr/prod/product/image/d6c7/G_CNF_N62842979_B.jpeg',
        'https://cdn.conforama.fr/prod/product/image/8723/G_CNF_A70656204_B.jpeg',
        'https://cdn.conforama.fr/prod/product/image/8f46/G_CNF_N62523575_B.jpeg'
    ];

    public const URL = [
        'https://www.ikea.com/fr/fr/p/fredde-bureau-gamer-noir-50219044/',
        'https://www.ikea.com/fr/fr/p/uppspel-bureau-gamer-noir-s29430165/',
        'https://www.ikea.com/fr/fr/p/malvakt-alex-bureau-noir-blanc-s69440011/',
        'https://www.ikea.com/fr/fr/p/utespelare-bureau-gamer-noir-80507627/',
        'https://www.conforama.fr/cuisine-salle-de-bain/meuble-de-cuisine/table-de-cuisine/table-brooklyn/p/742814',
        'https://www.conforama.fr/canape-salon-sejour/sejour/table/table-a-manger-georgia-8-personnes-' .
            'imitation-hetre-et-noire-160-cm/p/N62842979',
        'https://www.conforama.fr/canape-salon-sejour/sejour/table/table-scandinave-' .
            'extensible-inga-120-160-cm-blanche/p/A70656204',
        'https://www.conforama.fr/canape-salon-sejour/sejour/table/table-a-manger-rozy-' .
            '4-personnes-blanche-et-grise-110-cm/p/N62523575'
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::NAME as $key => $productName) {
            $product = new Product();
            $product->setName($productName);
            $product->setPrice(self::PRICE[$key]);
            $product->setPriceCurrency('€');
            $product->setHeight(self::HEIGHT[$key]);
            $product->setWidth(self::WIDTH[$key]);
            $product->setDepth(self::DEPTH[$key]);
            $product->setPicture(self::PICTURE[$key]);
            $product->setUrl(self::URL[$key]);
            $product->setPartnerProductId('Produit n°' . $key);
            $product->setPartner($this->getReference('partenaire_1'));
            $manager->persist($product);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            PartnerFixtures::class
        ];
    }
}
