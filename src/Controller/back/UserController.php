<?php


namespace App\Controller\back;


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
     */
    public function addUser(Request $request,
                            EntityManagerInterface $entityManager,
                            UserPasswordEncoderInterface $encoder
){
        $user = new User();
        $form = $this->createFormBuilder($user)
            ->add('name')
            ->add('firstname')
            ->add('pseudoName')
            ->add('email')
            ->add('password', PasswordType::class)
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin');
        }

        return $this->render('back/user/add.html.twig',[
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

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_admin');
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