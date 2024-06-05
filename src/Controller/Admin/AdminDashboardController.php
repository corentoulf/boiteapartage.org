<?php

namespace App\Controller\Admin;

use App\Entity\Circle;
use App\Entity\Item;
use App\Entity\ItemCategory;
use App\Entity\ItemCircle;
use App\Entity\User;
use App\Entity\UserCircle;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        // return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(ItemCategoryCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Boite A Partage');
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToRoute('Retourner sur l\'app', 'fa fa-external-link', 'app_auth_home'),
            MenuItem::section('Objets'),
            MenuItem::linkToCrud('Objets', 'fas fa-list', Item::class),
            MenuItem::linkToCrud('Objets & Cercles', 'fas fa-list', ItemCircle::class),
            MenuItem::linkToCrud('Catégories d\'objets', 'fas fa-list', ItemCategory::class),
            MenuItem::section('Utilisateurs'),
            MenuItem::linkToCrud('Utilisateurs', 'fas fa-user', User::class),
            MenuItem::linkToCrud('Cercles', 'fas fa-user', Circle::class),
            MenuItem::linkToCrud('Cercles - Utillisateurs', 'fas fa-user', UserCircle::class)
        ];
        //yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
    }
}
