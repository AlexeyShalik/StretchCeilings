<?php

namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin")
 */
class AdminController extends Controller
{
    /**
     * @Route("/", name="admin")
     */
    public function adminAction(Request $request)
    {
        return $this->redirectToRoute('fos_user_security_login');
    }

    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboardAction(Request $request)
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Статистика");
        // Example with parameter injected into translation "user.profile"
        //$breadcrumbs->addItem($txt, $url, ["%user%" => $user->getName()]);

        return $this->render('@App/admin/dashboard.html.twig');
    }

    /**
     * @Route("/user-manager", name="user_manager")
     */
    public function userManagerAction(Request $request)
    {
        $breadcrumbs = $this->getStartBreadcrumbs();
        $breadcrumbs->addItem("Управление пользователями");

        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT user FROM AppBundle:User user";
        $query = $em->createQuery($dql);

        $paginator  = $this->get('knp_paginator');
        $users = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            15
        );

        return $this->render('@App/admin/user-manager.html.twig', array('users' => $users));
    }

    private function getStartBreadcrumbs()
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Статистика", $this->get("router")->generate("dashboard"));

        return $breadcrumbs;
    }
}
