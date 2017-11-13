<?php

namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin")
 */
class DashboardController extends Controller
{
    /**
     * @Route("/", name="admin")
     */
    public function adminAction(Request $request)
    {
        return $this->redirectToRoute('fos_user_security_login');
    }

    /**
     * @Route("/dashboard", name="dashboard", methods={"GET"})
     */
    public function dashboardAction(Request $request)
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Статистика");

        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT COUNT(orders), orders.status FROM AppBundle:Order orders GROUP BY orders.status";
        $result  = $em->createQuery($dql)->getResult();
        $counts = array('Common' => 0);
        foreach ($result as $countStatus)
        {
            $counts[$countStatus['status']] = $countStatus[1];
            $counts['Common'] += $countStatus[1];
        }

        return $this->render('@App/admin/dashboard.html.twig',
            array('counts' => $counts));
    }
}
