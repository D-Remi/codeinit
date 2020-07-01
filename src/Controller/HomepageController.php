<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Document;
use App\Entity\Product;
use App\Form\ContactFormType;
use App\Form\DocType;
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

        $produtcs = $em->getRepository(Product::class)->findAll();

        $doc = new Document();
        $contact = new Contact();

        $formDoc = $this->createForm(DocType::class, $doc);
        $formDoc->handleRequest($request);
        if ($formDoc->isSubmitted() && $formDoc->isValid()){

            $em->persist($doc);
            $em->flush();
        }

        $form = $this->createForm(ContactFormType::class, $contact);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){

            $em->persist($contact);
            $em->flush();
            $this->addFlash('success',"Message a bien été envoyé");
        }

        return $this->render('front/homepage/index.html.twig',[
            'products' => $produtcs,
            'form'=> $form->createView(),
            'formDoc'=> $formDoc->createView()
        ]);
    }

    /**
     * @Route("/mentions-legales", name="app_mentions")
     */
    public function mention(){
        return $this->render('front/mentions-legale/mentios_legale.html.twig');
    }



}
