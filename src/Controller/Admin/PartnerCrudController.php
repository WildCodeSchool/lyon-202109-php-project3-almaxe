<?php

namespace App\Controller\Admin;

use App\Entity\Partner;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

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
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('name');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            ImageField::new('picture', 'Logo : ')
                ->setUploadDir('public/build/images/partner/')
                ->setRequired(false)
                ->onlyOnIndex(),
            UrlField::new('picture', 'Url du logo')
                ->onlyOnForms(),
            TextField::new('name', 'Nom du partenaire :'),
            UrlField::new('picture', 'Url Du Logo :')
                ->onlyOnForms(),
            BooleanField::new('active', 'Status :'),
            TextField::new('affiliateKey', 'Clef affilié :'),
        ];
    }
}
