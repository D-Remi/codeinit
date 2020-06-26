<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Product;
use App\Form\ContactFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class HomepageController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(Request $request,EntityManagerInterface $em)
    {
        $em = $this->getDoctrine()->getManager();
        $produtcs = $em->getRepository(Product::class)->findAll();

        $contact = new Contact();

        $form = $this->createForm(ContactFormType::class, $contact);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){

            $em->persist($contact);
            $em->flush();
        }

        return $this->render('homepage/index.html.twig',[
            'products' => $produtcs,
            'form'=> $form->createView()
        ]);
    }





}
