<?php

namespace App\DataFixtures;

use App\Entity\Partner;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PartnerFixtures extends Fixture
{
    public const PARTNERS_NAME = [
        'Ikea',
        'Maison du monde',
        'Conforama',
        'Meubles.fr',
        'Leroy Merlin',
        'Made',

    ];
    public const AFFILIATE_KEY = [
        'Affiliate Key Ikea',
        'Affiliate Key Maison du Monde',
        'Affiliate Key Conforama',
        'Affiliate Key Meubles.fr',
        'Affiliate Key Leroy Merlin',
        'Affiliate Key Made',
    ];

    public const ACTIVE = [
        true,
        true,
        true,
        true,
        true,
        true,
    ];

    public const PICTURE = [
        'https://business.ladn.eu/wp-content/uploads/2018/04/ikea-3.jpg?v=202112',
        'https://searchvectorlogo.com/wp-content/uploads/2020/09/maisons-du-monde-logo-vector.png',
        'https://logos-marques.com/wp-content/uploads/2021/03/Conforama-Logo-1987.png',
        'https://is3-ssl.mzstatic.com/image/thumb/Purple125/v4/1f/e4/4f/' .
            '1fe44fc4-4c31-2331-26de-02e9866f43c0/source/256x256bb.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/d/d4/Leroy_Merlin.svg/1200px-Leroy_Merlin.svg.png',
        'https://medias.oas.io/medias/2019/09/03/21/made-com-o.png',
    ];


    public function load(ObjectManager $manager)
    {
        foreach (self::PARTNERS_NAME as $key => $partnerName) {
            $partner = new Partner();
            $partner->setName($partnerName);
            $partner->setAffiliateKey(self::AFFILIATE_KEY[$key]);
            $partner->setActive(self::ACTIVE[$key]);
            $partner->setPicture(self::PICTURE[$key]);
            $manager->persist($partner);
            $this->addReference('partenaire_' . $key, $partner);
        }
        $manager->flush();
    }
}
