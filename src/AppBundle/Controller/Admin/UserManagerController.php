<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\User;
use AppBundle\Form\CreateEditUserType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/user-manager")
 */
class UserManagerController extends Controller
{
    /**
     * @Route("/", name="user_manager", methods={"GET"})
     */
    public function userManagerAction(Request $request)
    {
        $breadcrumbs = $this->getStartBreadcrumbs();
        $breadcrumbs->addItem("Управление пользователями");

        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT users FROM AppBundle:User users WHERE users.username <> 'superadmin'";
        $query = $em->createQuery($dql);

        $paginator  = $this->get('knp_paginator');
        $users = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            15
        );

        $deletedForms = [];
        foreach ($users as $user) {
            $deletedForms[$user->getId()] = $this->createDeleteUserForm($user)->createView();
        }

        return $this->render('@App/admin/user-manager.html.twig',
            array(
                'users' => $users,
                'deletedForms' => $deletedForms
            ));
    }

    /**
     * @Route("/create-user", name="create_user", methods={"GET", "POST"})
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
     * @Route("/{id}/edit-user", name="edit_user", methods={"GET", "POST"})
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

    /**
     * @Route("/{id}/delete", name="delete_user", methods={"DELETE"})
     */
    public function deleteUserAction(Request $request, User $user)
    {
        $form = $this->createDeleteUserForm($user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();

            $this->addFlash('info', sprintf('Управляющий "%s" был удален', $user->getUsername()));
        }

        return $this->redirectToRoute('user_manager');
    }

    private function createDeleteUserForm(User $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('delete_user', ['id' => $user->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }

    private function getStartBreadcrumbs()
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Статистика", $this->get("router")->generate("dashboard"));

        return $breadcrumbs;
    }
}
