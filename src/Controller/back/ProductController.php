<?php


namespace App\Controller\back;


use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class ProductController extends AbstractController
{
    /**
     * @Route("/administration/product", name="app_admin_product")
     */
    public function index(ProductRepository $productRepository){
        $products = $productRepository->findAll();

        return $this->render('back/product/index.html.twig',[
            'products' => $products
        ]);
    }

    /**
     * @Route("/administration/product/add", name="app_add_product")
     */
    public function addProduct(Request $request,
                            EntityManagerInterface $em
    ){
        $product = new Product();
        $form = $this->createFormBuilder($product)
            ->add('name')
            ->add('content')
            ->add('price')
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('app_admin');
        }

        return $this->render('back/product/add.html.twig',[
            'formProduct' => $form->createView()
        ]);
    }

    /**
     * @Route("/administration/product/update/{id}", name="app_update_product")
     */
    public function updateUser(ProductRepository $productRepository,
                               $id,
                               EntityManagerInterface $em,
                               Request  $request
    )
    {
        // je recupere les donnée utilisateur grace a l'id
        $product = $productRepository->find($id);

        $form = $this->createFormBuilder($product)
            ->add('name')
            ->add('content')
            ->add('price')
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('app_admin');
        }
        return $this->render('back/product/update.html.twig', array('formProduct'=> $form->createView()));
    }

    /**
     * @Route("/administration/product/delete/{id}", name="app_delete_product")
     */
    public function deleteUser(ProductRepository $productRepository,$id,EntityManagerInterface $em){
        // je recupere les donnée de la commande grace a l'id
        $product = $productRepository->find($id);
        // j'appel la suppression de l'objet recuperer grace a la methode remove
        $em->remove($product);
        // j'envoie la suppression avec la methode flush afin de supprimer l'utilisateur
        $em->flush();

        return $this->redirectToRoute('app_admin', array('product'=> $product));
    }
}