<?php

namespace AppBundle\Controller\Users;

use AppBundle\Entity\IpChecker;
use AppBundle\Entity\Order;
use AppBundle\Form\CallMeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UsersController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction(Request $request)
    {
        $form = $this->callMeFunction($request);
        return $this->render('@App/user/index.html.twig', array('callMeForm' => $form->createView(), 'page' => 'index'));
    }


    /**
     * @Route("/our-works", name="our-works")
     */
    public function ourWorksAction(Request $request)
    {
        $form = $this->callMeFunction($request);
        return $this->render('@App/user/our-works.html.twig', array('callMeForm' => $form->createView(), 'page' => 'our-works'));
    }

    private function callMeFunction(Request $request)
    {
        $order = new Order();
        $form = $this->createForm(CallMeType::class, $order);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            while (true){
                $order->setDateOrder(new \DateTime("now"));
                $order->setStatus('Waiting for the call');

                $repository = $this->getDoctrine()->getRepository(IpChecker::class);
                $ipChecker = $repository->findOneByIp($_SERVER['REMOTE_ADDR']);
                if($ipChecker !== null) {
                    $amount = $ipChecker->getAmount();
                    if($amount < 3) {
                        $ipChecker->setAmount($amount + 1);
                        $ipChecker->setDate($order->getDateOrder());
                    } else {
                        unset($order);
                        break;
                    }
                } else {
                    $ipChecker = new IpChecker();
                    $ipChecker->setIp($_SERVER['REMOTE_ADDR']);
                    $ipChecker->setAmount(1);
                    $ipChecker->setDate($order->getDateOrder());
                }

                $em = $this->getDoctrine()->getManager();
                $em->persist($order);
                $em->persist($ipChecker);
                $em->flush();
                break;
            }
        }
        return $form;
    }

}