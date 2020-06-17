<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $produtcs = $em->getRepository(Product::class)->findAll();

        return $this->render('homepage/index.html.twig',[
            'products' => $produtcs
        ]);
    }

    /**
     * @Route("/products/{id}", name="app_products")
     */
    public function products($id){

        $em = $this->getDoctrine()->getManager();
        $produtc = $em->getRepository(Product::class)->find($id);

        return $this->render('product/show_product.html.twig',[
            'products' => $produtc
        ]);
    }


}
