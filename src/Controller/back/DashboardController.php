<?php


namespace App\Controller\back;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/administration", name="app_admin")
     */
    public function index(){
       return $this->render('back/dashboard/back.html.twig');
   }
}