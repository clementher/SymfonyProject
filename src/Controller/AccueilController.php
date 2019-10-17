<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    /**
     * @Route("/")
     */
    function bonjour(){
        return $this->render('login.html.twig');
    }

    /**
     * @Route("/creneaux/{joker}")
     */
    function afficherCreneau($joker){
        return $this->render('affiche.html.twig',['title'=>ucwords(str_replace('-','',$joker))]);
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