<?php

namespace App\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DisponibiliteController extends AbstractController
{

    public function __construct(EntityManager $em)
    {
        $this->entityManager = $em;
    }

    /**
     * @Route("/dispo", name="dispo")
     */
    function dispo()
    {
        $arraydate = array();
        $user = $this->getUser();
        $idIntervenant = $user->getFkIntervenantId();
        $query1 = $this->entityManager->createQuery('SELECT d.debut as debut, d.fin as fin, identity(d.fk_intervenant_id) as intervenant from App\Entity\Disponibilite d
                                                        where d.fk_intervenant_id ='. $idIntervenant.'order by d.debut');
        $tabRetQuery = $query1->getResult();
        for($i=0;$i<count($tabRetQuery);$i++){
            array_push($arraydate,date_format($tabRetQuery[$i]['debut'],"d").'/'.date_format($tabRetQuery[$i]['debut'],"m").'/'.date_format($tabRetQuery[$i]['debut'],"Y"));
            array_push($arraydate,date_format($tabRetQuery[$i]['fin'],"d").'/'.date_format($tabRetQuery[$i]['fin'],"m").'/'.date_format($tabRetQuery[$i]['fin'],"Y"));
        }
        $this->console_log($arraydate);
        return $this->render('/dispo.html.twig', ['nb' => count($tabRetQuery), 'dates'=> $arraydate]);
    }

    /**
     * @Route("/addDispo/{var}")
     */
    function addDispo($var)
    {
        $dates = explode(";",$var);
        $jour1 = mktime(0,0,0,$dates[1],$dates[0],$dates[2]);
        $jour2 = mktime(0,0,0,$dates[4],$dates[3],$dates[5]);
        $user = $this->getUser();
        $idIntervenant = $user->getFkIntervenantId();
        $query2 = 'INSERT INTO Disponibilite (debut, fk_intervenant_id_id, fin) VALUES ('.date('Ymd', $jour1).','.$idIntervenant.','.date('Ymd', $jour2).')';
        $this->entityManager->getConnection()->executeUpdate($query2);
        return $this->redirectToRoute("dispo");
    }

    function console_log($data)
    {
        echo '<script>';
        echo 'console.log(' . json_encode($data) . ')';
        echo '</script>';
    }
}
