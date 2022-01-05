<?php

namespace App\Controller\Admin;

use App\Entity\Partner;
use App\Entity\Product;
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
            ->setTitle('<img src="/build/images/logo.7e5238b7.png" alt="Logo de pile poil" class="w-20 h-20">')
            ->setFaviconPath('/build/images/favicon.02074073.png')
            ;
    }

    public function configureMenuItems(): iterable
    {
        return [
        MenuItem::linktoRoute('Retour vers Pile Poil', 'fas fa-home', 'home'),
        MenuItem::section('Produits'),
        MenuItem::linkToCrud('Les produits', "fa fa-file-text", Product::class),
        MenuItem::section('Partenaire'),
        MenuItem::linkToCrud('Vos partenaire', "fas fa-comments", Partner::class),
        MenuItem::linkToCrud('Ajouter un partenaire', 'fa fa-tags', Partner::class)
        ->setAction('new'),
        MenuItem::linkToLogout('Déconnexion', 'fa fa-sign-out'),
        ];
    }
}
