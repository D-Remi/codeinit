<?php

namespace App\Controller\front;


use App\Entity\Order;
use App\Entity\Product;
use App\Form\OrderType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="app_cart")
     */
    public function index(Request $request)
    {
        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();

        $order = new Order();
        $products = array();
        $total = 0;
        if ($session->get('cart')) {
            foreach ($session->get('cart') as $cart) {
                $product = $em->getRepository(Product::class)->find($cart);
                $products[] = $product;
                $total = $total + $product->getPrice();
                $order->addProduct($product);
            }
        }

        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if($request->isMethod('POST') && $form->isValid()){
            $em->persist($order);
            $em->flush();

            $this->redirectToRoute('homepage');
        }

        return $this->render('front/cart/index.html.twig', [
            'products' => $products,
            'form' => $form->createView(),
        ]);
    }
}
