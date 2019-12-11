<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="app_login")
     */
    function index(){
        return $this->render('security/login.html.twig');
    }


    /**
     * @Route("/adminPanel")
     */
    function admin(){
        return $this->render('admin.html.twig');
    }

    /**
     * @Route("/repartition/{var}")
     */
    function repartition($var){
        $commentaires = [
            'Je ne pense pas que sa soit intéressant',
            'Mais si trkil',
            'ok mdr',
            ];
        $tab = array('-','_');
        return $this->render('affiche.html.twig',
            ['title'=>ucwords(str_replace('-','',
            $var)),
            'comments' => $commentaires]);
    }

}