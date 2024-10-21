<?php

namespace App\Controller\Admin;

use App\Entity\Disaster;
use App\Entity\Employee;
use App\Entity\Fine;
use App\Entity\FineCategory;
use App\Entity\Report;
use App\Entity\ReportCategory;
use App\Entity\User;
use App\Entity\Vehicule;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        if ($this->getUser()) {
            // L'utilisateur est connecté
            $user = $this->getUser();
            // Vous pouvez maintenant accéder aux propriétés de l'utilisateur ou effectuer des actions spécifiques
            return $this->render('admin/dashboard.html.twig');
        } else {
            // Aucun utilisateur n'est connecté
            return $this->redirect('/');
        }

        // return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
//         $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
//         return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());

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
            ->setTitle('Bomoko Cpanel');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section('Gestion des plaintes');
        yield MenuItem::linkToCrud('Signalements', 'fas fa-warning', Report::class);
        yield MenuItem::linkToCrud('Types de signalements', 'fas fa-cube', ReportCategory::class);
        yield MenuItem::linkToCrud('Amandes', 'fas fa-unlock', Fine::class);
        yield MenuItem::linkToCrud('Types d\'amandes', 'fa fa-sun-o', FineCategory::class);
        yield MenuItem::linkToCrud('Types de desastres', 'fa fa-fire', Disaster::class);
        yield MenuItem::section('Gestion Automobile');
        yield MenuItem::linkToCrud('Vehicule', 'fas fa-car', Vehicule::class);
        yield MenuItem::section("Gestion d'accès");
        yield MenuItem::linkToCrud('Employée', 'fas fa-user', Employee::class);
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-users', User::class);
    }
}
