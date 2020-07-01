<?php

namespace App\Controller\front;


use App\Entity\Order;
use App\Entity\Product;
use App\Entity\User;
use App\Form\OrderType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class CartController extends AbstractController
{
    /**
     * @Route("/cart/", name="app_cart")
     */
    public function index(Request $request)
    {
        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();


        $order = new Order();

        $products = array();
        $total = 0;
        if ($session->get('cart', [])) {
            foreach ($session->get('cart') as $cart) {
                $product = $em->getRepository(Product::class)->find($cart);
                $products[] = $product;
                $total = $total + $product->getPrice();
                $order->addProduct($product);
            }
        }
        $user = $this->getUser();

        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);
        if ($request->isMethod('POST') && $form->isValid()) {
            $order->setUser($user);
            $em->persist($order);
            $em->flush();
            $this->get('session')->set('cart', null);
            $this->addFlash('success',"Votre Commande a bien été pris en compte");
            return $this->redirectToRoute('profile');
        }
        return $this->render('front/cart/index.html.twig', [
            'products' => $products,
            'formOrder' => $form->createView(),
        ]);
    }

    /**
     * @Route("/cart/delete", name="app_delete_cart")
     */
    public function removeCart(Request $request){

        $this->get('session')->set('cart', null);

        return $this->redirectToRoute('app_cart');
    }

}
