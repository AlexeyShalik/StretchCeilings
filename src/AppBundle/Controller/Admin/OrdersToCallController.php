<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Order;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/orders-to-call")
 */
class OrdersToCallController extends Controller
{
    /**
     * @Route("/", name="orders_to_call", methods={"GET"})
     */
    public function orderManagerAction(Request $request)
    {
        $breadcrumbs = $this->getStartBreadcrumbs();
        $breadcrumbs->addItem("Управление заказами звонков");

        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT orders FROM AppBundle:Order orders";
        $query = $em->createQuery($dql);

        $paginator  = $this->get('knp_paginator');
        $orders = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            15
        );

        $deletedForms = [];
        foreach ($orders as $order) {
            $deletedForms[$order->getId()] = $this->createDeleteOrderForm($order)->createView();
        }

        return $this->render('@App/admin/order-manager.html.twig',
            array(
                'orders' => $orders,
                'deletedForms' => $deletedForms
            ));
    }

    private function createDeleteOrderForm(Order $order)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('delete_user', ['id' => $order->getId()]))
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