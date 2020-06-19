<?php


namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;


class ProductController extends AbstractController
{
    /**
     * @Route("/product/{id}", name="app_product")
     */
    public function index(Product $product,$id,Request $request){

        $session = new Session();
        if($request->query->get('addCart')){
            $productId = $product->getID();
            $cart = ($session->get('cart') != null) ? $session->get('cart') : [];
            $cart[] = $productId;
            $session->set('cart', $cart);

            return $this->redirectToRoute('homepage');
        }

        return $this->render('product/show_product.html.twig',[
            'product' => $product
        ]);
    }
}