<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Order;
use AppBundle\Form\CreateEditOrderType;
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

    /**
     * @Route("/create-order", name="create_order", methods={"GET", "POST"})
     */
    public function createOrderAction(Request $request)
    {
        $breadcrumbs = $this->getStartBreadcrumbs();
        $breadcrumbs->addItem("Управление заказами", $this->get("router")->generate("orders_to_call"));
        $breadcrumbs->addItem("Создание заказа");

        $order = new Order();
        $form = $this->createForm(CreateEditOrderType::class, $order);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $order->setDateOrder(date('Y-m-d H:i:s'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($order);
            $em->flush();

            return $this->redirectToRoute('orders_to_call');
        }

        return $this->render('@App/admin/create-order.html.twig', array('orderForm' => $form->createView()));
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
