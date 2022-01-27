<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use Doctrine\ORM\Mapping\Builder\ManyToManyAssociationBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

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

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('name')
            ->add('category');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addPanel('Produit'),
            TextField::new('name', 'Nom du produit :'),
            ImageField::new('picture', 'Logo :')
                ->setBasePath('public/build/images/product/')
                ->setUploadDir('public/build/images/product/')
                ->setRequired(false),
            UrlField::new('picture', 'Logo :')
                ->onlyOnForms(),
            UrlField::new('url', 'Lien vers le produit : ')
                ->onlyOnForms(),
            TextField::new('priceCurrency', 'Devise'),
            NumberField::new('price', 'Prix :')
                ->setRequired(true),
            IntegerField::new('height', 'Hauteur :'),
            IntegerField::new('width', 'Largeur :'),
            IntegerField::new('depth', 'Profondeur :'),
            SlugField::new('slug')
                ->setTargetFieldName('name')
                ->onlyOnForms(),
            FormField::addPanel('Catégorie'),
            AssociationField::new('category', 'Catégorie du produit :'),
            FormField::addPanel('Partenaire'),
            AssociationField::new('partner', 'Nom du partenaire :')
                ->setDisabled(true),
        ];
    }
}
