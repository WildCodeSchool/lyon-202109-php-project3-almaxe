<?php

namespace App\Controller\Admin;

use App\Entity\Partner;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PartnerCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Partner::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['name'])
            ->setDefaultSort(['name' => 'ASC']);
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            ImageField::new('picture', 'Url du Logo : ')
                ->setUploadDir('public/build/images/partner/')
                ->setRequired(false),
            TextField::new('picture', 'Url Du Logo :')
                ->onlyOnForms(),
            TextField::new('name', 'Nom du partenaire :'),
            BooleanField::new('active', 'Status :'),
            TextField::new('affiliateKey', 'Clé affilier :'),
        ];
    }
}
