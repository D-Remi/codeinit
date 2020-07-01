<?php


namespace App\Controller\back;


use App\Entity\Contact;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/administration", name="app_admin")
     */
    public function index(){

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findAll();
        //$Product = $em->getRepository(Product::class)->findAll();
        $order = $em->getRepository(Order::class)->findAll();
        $contact = $em->getRepository(Contact::class)->findAll();

        return $this->render('back/dashboard/back.html.twig',[
            'orderCount' => count($order),
            'userCount' => count($user),
            'contactCount' => count($contact)
        ]);
    }
}