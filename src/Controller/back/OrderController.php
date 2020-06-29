<?php


namespace App\Controller\back;


use App\Entity\Order;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
     * @Route("/administration/order", name="app_admin_order")
     */
    public function index(OrderRepository $orderRepository,Request $request,PaginatorInterface $paginator){
        $orders = $orderRepository->findBy([],
        ['id'=> 'desc']);

        $order = $paginator->paginate(
            $orders, //je passe les commandes
            $request->query->getInt('page', '1'),//numero de la page par defaut
            8
        );

        return $this->render('back/order/index.html.twig',[
            'orders' => $order
        ]);
    }

    /**
     * @Route("/administration/order/add", name="app_add_order")
     */
    public function addOrder(Request $request,
                            EntityManagerInterface $entityManager
    ){
        $order = new Order();
        $form = $this->createFormBuilder($order)
            ->add('email')
            ->add('startDate')
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $entityManager->persist($order);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin');
        }

        return $this->render('back/order/add.html.twig',[
            'formOrder' => $form->createView()
        ]);
    }

    /**
     * @Route("/administration/order/update/{id}", name="app_update_order")
     */
    public function updateOrder(OrderRepository $orderRepository,
                               $id,
                               EntityManagerInterface $em,
                               Request  $request
    )
    {
        // je recupere les donnée de la commande grace a l'id
        $order = $orderRepository->find($id);

        $form = $this->createFormBuilder($order)
            ->add('email')
            ->add('startDate')
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $em->persist($order);
            $em->flush();

            return $this->redirectToRoute('app_admin');
        }
        return $this->render('back/order/update.html.twig', array('formOrder'=> $form->createView()));
    }

    /**
     * @Route("/administration/order/delete/{id}", name="app_delete_order")
     */
    public function deleteOrder(OrderRepository $orderRepository,$id,EntityManagerInterface $em){
        // je recupere les donnée de la commande grace a l'id
        $order = $orderRepository->find($id);
        // j'appel la suppression de l'objet recuperer grace a la methode remove
        $em->remove($order);
        // j'envoie la suppression avec la methode flush afin de supprimer la commande
        $em->flush();

        return $this->redirectToRoute('app_admin', array('order'=> $order));
    }
}