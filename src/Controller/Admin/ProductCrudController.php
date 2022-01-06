<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['name', 'price', 'category.name'])
            ->setPaginatorPageSize(30);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::NEW);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom du produit :'),
            ImageField::new('picture', 'Logo :')
                ->setBasePath('public/build/images/product/')
                ->setUploadDir('public/build/images/product/')
                ->setRequired(false),
            TextField::new('picture', 'Logo :')
                ->onlyOnForms(),
            TextField::new('url', 'Lien vers le produit : ')
                ->onlyOnForms(),
            IntegerField::new('price', 'Prix :')
                ->setRequired(true),
            TextField::new('priceCurrency', 'Devise'),
            IntegerField::new('height', 'Hauteur :'),
            IntegerField::new('width', 'Largeur :'),
            IntegerField::new('depth', 'Profondeur :'),
            SlugField::new('slug')
                ->setTargetFieldName('name')
                ->onlyOnForms(),
            TextField::new('category.name', 'Categorie'),
            TextField::new('partner.name', 'Partenaire'),
        ];
    }
}
