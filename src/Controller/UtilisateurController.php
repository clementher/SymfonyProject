<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UtilisateurController extends AbstractController
{
    /**
     * @Route("/utilisateur", name="utilisateur")
     */
    public function index()
    {
        //Get entity Manager
        $entityManager = $this->getDoctrine()->getManager();
        $user = new Utilisateur();
        $user->setNom("Clément");
        $user->setPrenom("Hernandez");
        $user->setMail("clement.hernandez@limayrac.fr");
        $user->setTelephone("0643567899");
        $user->setPassword("testtest");
        $user->setIsAdmin(true);
        //informer Doctrine qu'on peut persister ces données
        $entityManager->persist($user);
        //Executer l requête
        $entityManager->flush();

        return $this->render('utilisateur/index.html.twig', [
            'controller_name' => 'UtilisateurController',
        ]);
    }
}
