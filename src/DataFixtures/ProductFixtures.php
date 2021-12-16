<?php

namespace App\DataFixtures;

use App\Entity\Blacklist;
use App\Entity\Partner;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public const NAME = [
        'FREDDE',
        'UPPSPEL',
        'MÅLVAKT / ALEX',
        'UTESPELARE'
    ];

    public const PRICE = [
        249,
        479,
        109.94,
        149
    ];

    public const HEIGHT = [
        146,
        123,
        73.4,
        78
    ];

    public const WIDTH = [
        185,
        140,
        120,
        160
    ];

    public const DEPTH = [
        74,
        80,
        80,
        80
    ];

    public const PICTURE = [
        'https://www.ikea.com/fr/fr/images/products/fredde-bureau-gamer-noir__0736008_pe740351_s5.jpg?f=xl',
        'https://www.ikea.com/fr/fr/images/products/uppspel-bureau-gamer-noir__1039777_pe840447_s5.jpg?f=xl',
        'https://www.ikea.com/fr/fr/images/products/malvakt-alex-bureau-noir-blanc__1032042_pe836750_s5.jpg?f=xl',
        'https://www.ikea.com/fr/fr/images/products/utespelare-bureau-gamer-noir__0985179_pe816538_s5.jpg?f=xl'
    ];

    public const URL = [
        'https://www.ikea.com/fr/fr/p/fredde-bureau-gamer-noir-50219044/',
        'https://www.ikea.com/fr/fr/p/uppspel-bureau-gamer-noir-s29430165/',
        'https://www.ikea.com/fr/fr/p/malvakt-alex-bureau-noir-blanc-s69440011/',
        'https://www.ikea.com/fr/fr/p/utespelare-bureau-gamer-noir-80507627/'
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
            $product->setPartner($this->getReference('partenaire_0'));
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
