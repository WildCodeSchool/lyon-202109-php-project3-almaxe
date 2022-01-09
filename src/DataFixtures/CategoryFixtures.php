<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public const NAME = [
        'table',
        'bureau',
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::NAME as $key => $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            $this->addReference('category_' . $key, $category);
            $manager->persist($category);
        }
        $manager->flush();
    }
}
