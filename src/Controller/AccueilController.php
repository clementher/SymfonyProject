<?php


namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AccueilController
{

    /**
     * @Route("/")
     */
    function bonjour(){
        return new Response('bonjour M1ESI !');
    }

    /**
     * @Route("/creneaux/{joker}")
     */

    function affichageCreneau($joker){
        $message = sprintf("Future page d'Affichage de Créneaux : %s",$joker);
        return new Response($message);
    }
}