<?php

namespace App\Controller\Admin;

use App\Entity\Genre;
use App\Entity\Livre;
use App\Entity\Auteur;
use App\Entity\Editeur;
use App\Entity\Nationalite;
use App\Controller\Admin\AuteurCrudController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin")
     */
    public function index(): Response
    {
        // redirect to some CRUD controller
        $routeBuilder = $this->get(AdminUrlGenerator::class);

        return $this->redirect($routeBuilder->setController(AuteurCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Tp2PPE4');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        // yield MenuItem::section(label:'Important');
        yield MenuItem::linkToCrud('Auteurs', 'fas fa-list', Auteur::class);
        yield MenuItem::linkToCrud('Nationalite', 'fas fa-list', Nationalite::class);
        yield MenuItem::linkToCrud('Genre', 'fas fa-list', Genre::class);
        yield MenuItem::linkToCrud('Editeurs', 'fas fa-list', Editeur::class);
        yield MenuItem::linkToCrud('Livre', 'fas fa-list', Livre::class);

        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
