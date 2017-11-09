<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\User;
use AppBundle\Form\CreateEditUserType;

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

    /**
     * @Route("/user-manager/create-user", name="create_user")
     */
    public function createUserAction(Request $request)
    {
        $breadcrumbs = $this->getStartBreadcrumbs();
        $breadcrumbs->addItem("Управление пользователями", $this->get("router")->generate("user_manager"));
        $breadcrumbs->addItem("Создание управляющего");

        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->createUser();
        $form = $this->createForm(CreateEditUserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->addRole('ROLE_MANAGER');
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('user_manager');
        }

        return $this->render('@App/admin/create-user.html.twig', array('userForm' => $form->createView()));
    }

    /**
     * @Route("/user-manager/{id}/edit-user", name="edit_user")
     */
    public function editUserAction(Request $request, User $user)
    {
        $breadcrumbs = $this->getStartBreadcrumbs();
        $breadcrumbs->addItem("Управление пользователями", $this->get("router")->generate("user_manager"));
        $breadcrumbs->addItem("Редактирование управляющего - ".$user->getUsername());

        $form = $this->createForm(CreateEditUserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('fos_user.user_manager')->updateUser($user);

            return $this->redirectToRoute('user_manager');
        }

        return $this->render('@App/admin/edit-user.html.twig', array('userForm' => $form->createView()));
    }


    private function getStartBreadcrumbs()
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Статистика", $this->get("router")->generate("dashboard"));

        return $breadcrumbs;
    }
}
