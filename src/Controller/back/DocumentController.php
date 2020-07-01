<?php


namespace App\Controller\back;


use App\Entity\Document;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DocumentController extends AbstractController
{

    /**
     * @Route("/administration/doc", name="app_admin_doc")
     */
    public function index(){

        $em = $this->getDoctrine()->getManager();
        $docs = $em->getRepository(Document::class)->findAll();

        return $this->render('back/document/index.html.twig',[
            'Docs' => $docs
        ]);
    }
}