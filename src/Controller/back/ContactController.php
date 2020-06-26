<?php

namespace App\Controller\back;

use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/administration/contact", name="app_admin_contact")
     */
    public function index(ContactRepository $contactRepository){
        $contact = $contactRepository->findAll();

        return $this->render('back/contact/index.html.twig',[
            'contacts' => $contact
        ]);
    }

    /**
     * @Route("/administration/contact/{id}", name="app_show_contact")
     */
    public function showContact(ContactRepository $contactRepository,$id){
        $contact = $contactRepository->find($id);

        return $this->render('back/contact/show.html.twig',[
            'contact' => $contact
        ]);
    }

    /**
     * @Route("/administration/delete/{id}", name="app_delete_contact")
     */
    public function addContact(ContactRepository $contactRepository,$id,EntityManagerInterface $em){

        $contact = $contactRepository->find($id);
        $em->remove($contact);
        $em->flush();

        $this->addFlash('success',"Message Supprimmer");

        return $this->redirectToRoute('app_admin_contact');
    }
}
