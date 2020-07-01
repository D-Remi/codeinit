<?php

namespace App\Controller\front;

use App\Form\UserFormType;
use App\Repository\UserRepository;
use \Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     */
    public function index(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $form = $this->createForm(UserFormType::class,$user);

        if($request->isMethod('POST') && $form->handleRequest($request)){
            $em->persist($user);
            $em->flush();
        }

        return $this->render('front/profile/index.html.twig',[
            'formUser' => $form->createView()
        ]);
    }


}
