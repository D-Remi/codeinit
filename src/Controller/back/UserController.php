<?php

// le namespace qui correspond au chemin vers la classe
// (en gardant en tête que "App" = "src")
namespace App\Controller\back;

//"use" vers le namespace (qui correspond au chemin) de la classe "Route"
// ça correspond à un import ou un require en PHP
// pour pouvoir utiliser ces classe dans mon code
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/administration/user", name="app_admin_user")
     */
    public function index(UserRepository $userRepository){
        $users = $userRepository->findAll();

        return $this->render('back/user/index.html.twig',[
            'users' => $users
        ]);
    }

    /**
     * @Route("/administration/user/add", name="app_add_user")
     *
     * j'utilise l'autowire de symfony pour instancier les classes Request,
     * EntityManagerInterface,UserPasswordEncoderInterface dans leur variable respective
     */
    public function addUser(Request $request,
                            EntityManagerInterface $entityManager,
                            UserPasswordEncoderInterface $encoder
){      // permettre de créer un nouveau utilisateur qui sera dans la variable $user
        // new User Correspond a l'entité User
        $user = new User();
        // je créé mon formulaire, et je le lie à mon nouveau utilisateur
        $form = $this->createFormBuilder($user)
            ->add('name')
            ->add('firstname')
            ->add('pseudoName')
            ->add('email')
            ->add('password', PasswordType::class)
            ->getForm();
        // je demande à mon formulaire $form de gérer les données
        // de la requête POST
        $form->handleRequest($request);
        // si le formulaire a été envoyé, et que les données sont valides
        if($form->isSubmitted() && $form->isValid()){
            // pour  que le mot de passe soit crypté lors de la creation d'un utilisateur
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            // je Persist l'utilisateur créer
            $entityManager->persist($user);
            $entityManager->flush();
            // j'ajoute un message "flash"
            $this->addFlash('success', 'l\'utilisateur a bien été créer !');
            //j'effectue une redirection une fois l'user creer
            return $this->redirectToRoute('app_admin_user');
        }

        return $this->render('back/user/add.html.twig',[
            //
            'formUser' => $form->createView()
        ]);
    }

    /**
     * @Route("/administration/user/update/{id}", name="app_update_user")
     */
    public function updateUser(UserRepository $userRepository,
                               $id,
                               EntityManagerInterface $em,
                               Request  $request
                                )
    {
        // je recupere les donnée utilisateur grace a l'id
        $user = $userRepository->find($id);

        $form = $this->createFormBuilder($user)
            ->add('name')
            ->add('firstname')
            ->add('pseudoName')
            ->add('email')
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->addFlash('success',"utilisateur a jours ");
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_admin_user');
        }
        return $this->render('back/user/update.html.twig', array('formUser'=> $form->createView()));
    }

    /**
     * @Route("/administration/user/delete/{id}", name="app_delete_user")
     */
    public function deleteUser(UserRepository $userRepository,$id,EntityManagerInterface $em){
        // je recupere les donnée utilisateur grace a l'id
        $user = $userRepository->find($id);
        // j'appel la suppression de l'objet recuperer grace a la methode remove
        $em->remove($user);
        // j'envoie la suppression avec la methode flush afin de supprimer l'utilisateur
        $em->flush();

        return $this->redirectToRoute('app_admin', array('user'=> $user));
    }
}