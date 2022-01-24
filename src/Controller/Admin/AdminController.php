<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Partner;
use App\Entity\Product;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractDashboardController
{
    private AdminUrlGenerator $adminUrlGenerator;

    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    /**
     * @Route("/admin", name="admin_dashboard")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function index(): Response
    {
        return $this->redirect($this->adminUrlGenerator->setController(ProductCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Pile Poil')
            ->setTitle('<img src="/images/logo.png" alt="Logo de pile poil" class="w-10 h-10">')
            ->setFaviconPath('/images/favicon.png')
            ;
    }

    public function configureMenuItems(): iterable
    {
        return [
        MenuItem::section('Menu'),
        MenuItem::linktoRoute('Retour vers Pile Poil', 'fas fa-home', 'home'),
        MenuItem::linkToLogout('Déconnexion', 'fa fa-sign-out'),
        MenuItem::section('Produits'),
        MenuItem::linkToCrud('Les produits', "fa fa-file-text", Product::class),
        MenuItem::section('Catégorie'),
        MenuItem::linkToCrud('Les catégories', "fa fa-file-text", Category::class),
        MenuItem::section('Partenaire'),
        MenuItem::linkToCrud('Vos partenaire', "fas fa-comments", Partner::class),
        MenuItem::linkToCrud('Ajouter un partenaire', 'fa fa-tags', Partner::class)
            ->setAction('new'),
        MenuItem::section('Utilisateurs'),
        MenuItem::linkToCrud('Utilisateurs', "fa fa-file-text", User::class),
        ];
    }
}
