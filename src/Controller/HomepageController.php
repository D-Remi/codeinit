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



}
