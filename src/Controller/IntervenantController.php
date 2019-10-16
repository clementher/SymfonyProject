<?php

namespace App\Controller;

use App\Entity\Intervenant;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IntervenantController extends AbstractController
{
    /**
     * @Route("/intervenant", name="intervenant")
     */
    public function index()
    {
        //Get entity Manager
        $entityManager = $this->getDoctrine()->getManager();

        //$inter = new Intervenant();
        //$inter->setNom("Clément");
        //$inter->setPrenom("Loic");
        //$inter->setSpecialiteprofessionnelle("Cock Sucker");

        //informer Doctrine qu'on peut persister ces données
        //$entityManager->persist($inter);
        //Executer l requête
        //$entityManager->flush();

        return $this->render('intervenant/index.html.twig', [
            'controller_name' => 'IntervenantController',
        ]);
    }

    /**
     * @Route("/intervenant_show/{id}", name="intervenant_show")
     */
    public function show($id)
    {
        $inter = $this->getDoctrine()->getRepository(Intervenant::class)->find($id);

        if(!$inter){
            throw $this->createNotFoundException("Not found you scumbag" . $id);
        }
        return new Response("id numéro".$id.", nom : ".$inter->getNom());
    }
}
