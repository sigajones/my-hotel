<?php

namespace App\Controller\Admin;

use App\Entity\Membre;
use App\Entity\Slider;
use App\Entity\Chambre;
use App\Entity\Comment;
use App\Entity\Commande;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    public function __construct(private AdminUrlGenerator $adminUrlGenerator)
    {
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $url = $this->adminUrlGenerator->setController(ChambreCrudController::class)->generateUrl();
        //redirige la page d'accueil du backoffice vers ce crud
        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('My Hotel');
    }

    public function configureMenuItems(): iterable
    {
        // yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
        return [
            MenuItem::linkToDashboard("BACKOFFICE", 'fa fa-home'),
            MenuItem::section('Membres'),
            MenuItem::linkToCrud('Utilisateurs', 'fas fa-user', Membre::class),
            MenuItem::section('Chambres'),
            MenuItem::linkToCrud('Chambres', 'fa fa-newspaper', Chambre::class),
            MenuItem::linkToCrud('Commentaires', 'fa fa-comment', Comment::class),
            MenuItem::linkToCrud('Sliders', 'fa fa-', Slider::class),
            MenuItem::section('Commandes'),
            MenuItem::linkToCrud('Commandes', 'fas fa-cart', Commande::class),
            MenuItem::section('Retour au site'),
            MenuItem::linkToRoute('Accueil du site', 'fa fa-igloo', 'home'),
        ];
    }
}
