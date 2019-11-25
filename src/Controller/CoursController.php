<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Entity\Intervenant;
use App\Entity\Matiere;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManager;

class CoursController extends AbstractController
{
    public $routeDetailSem = 'creneauDetailSemaine';
    private  $em;

    public function __construct(EntityManager $em) {
        $this->entityManager = $em;

    }

    /**
     * @Route("/week/{noann}/{nosem}", name="creneauDetailSemaine")
     */
    function afficherCreneauSemaine($noann,$nosem){
        if ($nosem <=53 && $nosem >1) {
            $jour1 = mktime(0, 0, 0, (new DateTime())->setISODate($noann, $nosem)->format('m'), (new DateTime())->setISODate($noann, $nosem)->format('d'), $noann);
            $jour2 = mktime(0, 0, 0, (new DateTime())->setISODate($noann, $nosem)->format('m'), (new DateTime())->setISODate($noann, $nosem)->format('d')+1, $noann);
            $jour3 = mktime(0, 0, 0, (new DateTime())->setISODate($noann, $nosem)->format('m'), (new DateTime())->setISODate($noann, $nosem)->format('d')+2, $noann);
            $jour4 = mktime(0, 0, 0, (new DateTime())->setISODate($noann, $nosem)->format('m'), (new DateTime())->setISODate($noann, $nosem)->format('d')+3, $noann);
            $jour5 = mktime(0, 0, 0, (new DateTime())->setISODate($noann, $nosem)->format('m'), (new DateTime())->setISODate($noann, $nosem)->format('d')+4, $noann);

            $tabCours = $this->initTabs($this->setTabs($jour1, $jour2, $jour3, $jour4, $jour5));
            //$tab1 = $tabCours[0];

            $tab = array(
                array($this->deterJour(date("N", $jour1)),
                    date("j",$jour1),
                    $this->deterMois(date("m",$jour1))),
                array($this->deterJour(date("N", $jour2)),
                    date("j", $jour2),
                    $this->deterMois(date("m", $jour2))),
                array($this->deterJour(date("N",$jour3)),
                    date("j",$jour3),
                    $this->deterMois(date("m", $jour3))),
                array($this->deterJour(date("N", $jour4)),
                    date("j", $jour4),
                    $this->deterMois(date("m", $jour4))),
                array($this->deterJour(date("N", $jour5)),
                    date("j", $jour5),
                    $this->deterMois(date("m", $jour5)))
            );
            return $this->render('/cours/week.html.twig', ['tab' => $tab, 'noSem' => $nosem,'noAnn' => $noann, 'compteur' => $tabCours]);
        }
        elseif ($nosem == 1 || $nosem == 54){
            if ($nosem == 54) {
                return $this->redirectToRoute("creneauDetailSemaine",array('nosem'=>2, 'noann' =>$noann+1));
            }
            if ($nosem == 1) {
                return $this->redirectToRoute("creneauDetailSemaine", array('nosem' => 53, 'noann' => $noann - 1));
            }
        }
        else {
            throw $this->createNotFoundException("Ce numéro de semaine n'existe pas.");
        }
    }

    /**
     * @Route("/month")
     */
    function afficher(){
        return $this->render('/cours/month.html.twig');
    }

    /**
     * @Route("/week")
     */
    function afficherCreneau(){
        $nosem = date("W");
        $noann = date("Y");
        return $this->redirectToRoute("creneauDetailSemaine",array('nosem'=>$nosem, 'noann' =>$noann));
    }

    function deterJour($numjour){
        switch ($numjour){
            case 1:
                return 'Lundi';
            case 2:
                return 'Mardi';
            case 3:
                return 'Mercredi';
            case 4:
                return 'Jeudi';
            case 5:
                return 'Vendredi';
            default:
                return 'Erreur';
        }
    }

    function deterMois($nummois)
    {
        switch ($nummois) {
            case 1:
                return 'Janvier';
            case 2:
                return 'Fevrier';
            case 3:
                return 'Mars';
            case 4:
                return 'Avril';
            case 5:
                return 'Mai';
            case 6:
                return 'Juin';
            case 7:
                return 'Juillet';
            case 8:
                return 'Aout';
            case 9:
                return 'Septembre';
            case 10:
                return 'Octobre';
            case 11:
                return 'Novembre';
            case 12:
                return 'Decembre';
            default:
                return 'Erreur';
        }
    }


        function setTabs($jour1, $jour2, $jour3, $jour4, $jour5){

            $query1 = $this->entityManager -> createQuery('SELECT m.intitule AS intitule, i.nom as nom, i.prenom as prenom, c.debut as debut, c.fin as fin, m.isSpecialite as spe FROM App\Entity\Matiere m, App\Entity\Intervenant i, App\Entity\Cours c
                        WHERE m.id = c.fk_matiere_id AND i.id = c.fk_intervenant_id and c.fk_intervenant_id = 1 and c.debut >= '.date('Ymd', $jour1).' AND c.debut < '.date('Ymd',$jour2).' ORDER BY c.debut');
            /**$this->entityManager ->createQueryBuilder()->select('c')
                -> from(Cours::class, 'c') -> where('c.fk_intervenant_id = 1') -> andWhere('c.debut >= '.date('Ymd', $jour1)) -> andWhere('c.debut < '.date('Ymd',$jour2))
                -> getQuery();**/

            $tab1 = $query1->getResult();

            $query2 = $this->entityManager -> createQuery('SELECT m.intitule AS intitule, i.nom as nom, i.prenom as prenom, c.debut as debut, c.fin as fin, m.isSpecialite as spe FROM App\Entity\Matiere m, App\Entity\Intervenant i, App\Entity\Cours c
                        WHERE m.id = c.fk_matiere_id AND i.id = c.fk_intervenant_id and c.fk_intervenant_id = 1 and c.debut >= '.date('Ymd', $jour2).' AND c.debut < '.date('Ymd',$jour3).' ORDER BY c.debut');
                /**$this->entityManager ->createQueryBuilder()->select('c')
                -> from(Cours::class, 'c') -> where('c.fk_intervenant_id = 1') -> andWhere('c.debut > :date1') -> andWhere('c.debut < :date2')
                -> setParameter(':date1',date('Y-m-d', $jour2)) -> setParameter(':date2', date('Y-m-d',$jour3)) -> getQuery();**/
            $tab2 = $query2->getResult();

            $query3 = $this->entityManager -> createQuery('SELECT m.intitule AS intitule, i.nom as nom, i.prenom as prenom, c.debut as debut, c.fin as fin, m.isSpecialite as spe FROM App\Entity\Matiere m, App\Entity\Intervenant i, App\Entity\Cours c
                        WHERE m.id = c.fk_matiere_id AND i.id = c.fk_intervenant_id and c.fk_intervenant_id = 1 and c.debut >= '.date('Ymd', $jour3).' AND c.debut < '.date('Ymd',$jour4).' ORDER BY c.debut');
                /**$this->entityManager ->createQueryBuilder()->select('c')
                -> from(Cours::class, 'c') -> where('c.fk_intervenant_id = 1') -> andWhere('c.debut > :date1') -> andWhere('c.debut < :date2')
                -> setParameter(':date1', date('Y-m-d',$jour3)) -> setParameter(':date2', date('Y-m-d',$jour4)) -> getQuery();**/
            $tab3 = $query3->getResult();

            $query4 =  $this->entityManager -> createQuery('SELECT m.intitule AS intitule, i.nom as nom, i.prenom as prenom, c.debut as debut, c.fin as fin, m.isSpecialite as spe FROM App\Entity\Matiere m, App\Entity\Intervenant i, App\Entity\Cours c
                        WHERE m.id = c.fk_matiere_id AND i.id = c.fk_intervenant_id and c.fk_intervenant_id = 1 and c.debut >= '.date('Ymd', $jour4).' AND c.debut < '.date('Ymd',$jour5).' ORDER BY c.debut');
                /**$this->entityManager ->createQueryBuilder()->select('c')
                -> from(Cours::class, 'c') -> where('c.fk_intervenant_id = 1') -> andWhere('c.debut > :date1') -> andWhere('c.debut < :date2')
                -> setParameter(':date1', date('Y-m-d',$jour4)) -> setParameter(':date2', date('Y-m-d',$jour5)) -> getQuery();**/
            $tab4 = $query4->getResult();

            $query5 = $this->entityManager -> createQuery('SELECT m.intitule AS intitule, i.nom as nom, i.prenom as prenom, c.debut as debut, c.fin as fin, m.isSpecialite as spe FROM App\Entity\Matiere m, App\Entity\Intervenant i, App\Entity\Cours c
                        WHERE m.id = c.fk_matiere_id AND i.id = c.fk_intervenant_id and c.fk_intervenant_id = 1 and c.debut >= '.date('Ymd', $jour5).' AND c.debut < '.date('Ymd',$jour5 + 86400).' ORDER BY c.debut');
                /**$this->entityManager ->createQueryBuilder()->select('c')
                -> from(Cours::class, 'c') -> where('c.fk_intervenant_id = 1') -> andWhere('c.debut >'. date('Y-m-d',$jour5)) -> andWhere('c.debut <'.date('Y-m-d',$jour5+86400))
                -> getQuery();**/
            $tab5 = $query5->getResult();

            return array($tab1, $tab2, $tab3, $tab4, $tab5);
        }

        function initTabs($tab){
            $tabJour1 = $tab[0];
            $tabRet1 = array();
            for ($i=0; $i<=count($tabJour1); $i++) {
                if (count($tabJour1)==2)
                {
                    $duree1 = date_diff($tabJour1[0]['debut'],$tabJour1[0]['fin'])->format('%h');
                    $duree2 = date_diff($tabJour1[1]['debut'],$tabJour1[1]['fin'])->format('%h');
                    for($j=1;$j<=$duree1;$j++){
                        array_push($tabRet1,array('intitule' => $tabJour1[0]['intitule'], 'intervenant' => $tabJour1[0]['nom'].' '.$tabJour1[0]['prenom']));
                    }
                    for($j=1;$j<=2+(4-$duree1);$j++){
                        array_push($tabRet1,array('intitule' => ' ', 'intervenant' => ' '));
                    }
                    for($j=1;$j<=$duree2;$j++){
                        array_push($tabRet1,array('intitule' => $tabJour1[1]['intitule'], 'intervenant' =>$tabJour1[1]['nom'].' '.$tabJour1[1]['prenom']));
                    }
                    for($j=1;$j<=3-$duree2;$j++){
                        array_push($tabRet1,array('intitule' => ' ', 'intervenant' => ' '));
                    }
                } elseif (count($tabJour1)==1){
                    $duree1 = date_diff($tabJour1[0]['debut'],$tabJour1[0]['fin'])->format('%h');
                    $this->console_log($tabJour1[0]['debut']->format('H'));
                    if($tabJour1[0]['debut']->format('H') == '09'){
                        $this->console_log('il est 9h');
                        for($j=1;$j<=$duree1;$j++){
                            array_push($tabRet1,array('intitule' => $tabJour1[0]['intitule'], 'intervenant' => $tabJour1[0]['nom'].' '.$tabJour1[0]['prenom']));
                        }
                        for($j=1;$j<=5+(4-$duree1);$j++){
                            array_push($tabRet1,array('intitule' => ' ', 'intervenant' => ' '));
                        }
                    }elseif ($tabJour1[0]['debut']->format('H') == '15'){
                        $this->console_log('il est 15h');
                        for($j=1;$j<=6;$j++){
                            array_push($tabRet1,array('intitule' => ' ', 'intervenant' => ' '));
                        }
                        for($j=1;$j<=$duree1;$j++){
                            array_push($tabRet1,array('intitule' => $tabJour1[0]['intitule'], 'intervenant' => $tabJour1[0]['nom'].' '.$tabJour1[0]['prenom']));
                        }
                        for($j=1;$j<=3-$duree1;$j++){
                            array_push($tabRet1,array('intitule' => ' ', 'intervenant' => ' '));
                        }
                    }
                } else {
                    for($j=1;$j<=9;$j++){
                        array_push($tabRet1,array('intitule' => ' ', 'intervenant' => ' '));
                    }
                }
            }

            $tabJour2 = $tab[1];
            $tabRet2 = array();
            for ($i=0; $i<=count($tabJour2); $i++) {
                if (count($tabJour2)==2)
                {
                    $duree1 = date_diff($tabJour2[0]['debut'],$tabJour2[0]['fin'])->format('%h');
                    $duree2 = date_diff($tabJour2[1]['debut'],$tabJour2[1]['fin'])->format('%h');
                    for($j=1;$j<=$duree1;$j++){
                        array_push($tabRet2,array('intitule' => $tabJour2[0]['intitule'], 'intervenant' => $tabJour2[0]['nom'].' '.$tabJour2[0]['prenom']));
                    }
                    for($j=1;$j<=2+(4-$duree1);$j++){
                        array_push($tabRet2,array('intitule' => ' ', 'intervenant' => ' '));
                    }
                    for($j=1;$j<=$duree2;$j++){
                        array_push($tabRet2,array('intitule' => $tabJour2[1]['intitule'], 'intervenant' =>$tabJour2[1]['nom'].' '.$tabJour2[1]['prenom']));
                    }
                    for($j=1;$j<=3-$duree2;$j++){
                        array_push($tabRet2,array('intitule' => ' ', 'intervenant' => ' '));
                    }
                } elseif (count($tabJour2)==1){
                    $duree1 = date_diff($tabJour2[0]['debut'],$tabJour2[0]['fin'])->format('%h');
                    $this->console_log($tabJour2[0]['debut']->format('H'));
                    if($tabJour2[0]['debut']->format('H') == '09'){
                        $this->console_log('il est 9h');
                        for($j=1;$j<=$duree1;$j++){
                            array_push($tabRet2,array('intitule' => $tabJour2[0]['intitule'], 'intervenant' => $tabJour2[0]['nom'].' '.$tabJour2[0]['prenom']));
                        }
                        for($j=1;$j<=5+(4-$duree1);$j++){
                            array_push($tabRet2,array('intitule' => ' ', 'intervenant' => ' '));
                        }
                    }elseif ($tabJour2[0]['debut']->format('H') == '15'){
                        $this->console_log('il est 15h');
                        for($j=1;$j<=6;$j++){
                            array_push($tabRet2,array('intitule' => ' ', 'intervenant' => ' '));
                        }
                        for($j=1;$j<=$duree1;$j++){
                            array_push($tabRet2,array('intitule' => $tabJour2[0]['intitule'], 'intervenant' => $tabJour2[0]['nom'].' '.$tabJour2[0]['prenom']));
                        }
                        for($j=1;$j<=3-$duree1;$j++){
                            array_push($tabRet2,array('intitule' => ' ', 'intervenant' => ' '));
                        }
                    }
                } else {
                    for($j=1;$j<=9;$j++){
                        array_push($tabRet2,array('intitule' => ' ', 'intervenant' => ' '));
                    }
                }
            }

            $tabjour3 = $tab[2];
            $tabret3 = array();
            for ($i=0; $i<=count($tabjour3); $i++) {
                if (count($tabjour3)==2)
                {
                    $duree1 = date_diff($tabjour3[0]['debut'],$tabjour3[0]['fin'])->format('%h');
                    $duree2 = date_diff($tabjour3[1]['debut'],$tabjour3[1]['fin'])->format('%h');
                    for($j=1;$j<=$duree1;$j++){
                        array_push($tabret3,array('intitule' => $tabjour3[0]['intitule'], 'intervenant' => $tabjour3[0]['nom'].' '.$tabjour3[0]['prenom']));
                    }
                    for($j=1;$j<=2+(4-$duree1);$j++){
                        array_push($tabret3,array('intitule' => ' ', 'intervenant' => ' '));
                    }
                    for($j=1;$j<=$duree2;$j++){
                        array_push($tabret3,array('intitule' => $tabjour3[1]['intitule'], 'intervenant' =>$tabjour3[1]['nom'].' '.$tabjour3[1]['prenom']));
                    }
                    for($j=1;$j<=3-$duree2;$j++){
                        array_push($tabret3,array('intitule' => ' ', 'intervenant' => ' '));
                    }
                } elseif (count($tabjour3)==1){
                    $duree1 = date_diff($tabjour3[0]['debut'],$tabjour3[0]['fin'])->format('%h');
                    $this->console_log($tabjour3[0]['debut']->format('H'));
                    if($tabjour3[0]['debut']->format('H') == '09'){
                        $this->console_log('il est 9h');
                        for($j=1;$j<=$duree1;$j++){
                            array_push($tabret3,array('intitule' => $tabjour3[0]['intitule'], 'intervenant' => $tabjour3[0]['nom'].' '.$tabjour3[0]['prenom']));
                        }
                        for($j=1;$j<=5+(4-$duree1);$j++){
                            array_push($tabret3,array('intitule' => ' ', 'intervenant' => ' '));
                        }
                    }elseif ($tabjour3[0]['debut']->format('H') == '15'){
                        $this->console_log('il est 15h');
                        for($j=1;$j<=6;$j++){
                            array_push($tabret3,array('intitule' => ' ', 'intervenant' => ' '));
                        }
                        for($j=1;$j<=$duree1;$j++){
                            array_push($tabret3,array('intitule' => $tabjour3[0]['intitule'], 'intervenant' => $tabjour3[0]['nom'].' '.$tabjour3[0]['prenom']));
                        }
                        for($j=1;$j<=3-$duree1;$j++){
                            array_push($tabret3,array('intitule' => ' ', 'intervenant' => ' '));
                        }
                    }
                } else {
                    for($j=1;$j<=9;$j++){
                        array_push($tabret3,array('intitule' => ' ', 'intervenant' => ' '));
                    }
                }
            }

            $tabjour4 = $tab[3];
            $tabret4 = array();
            for ($i=0; $i<=count($tabjour4); $i++) {
                if (count($tabjour4)==2)
                {
                    $duree1 = date_diff($tabjour4[0]['debut'],$tabjour4[0]['fin'])->format('%h');
                    $duree2 = date_diff($tabjour4[1]['debut'],$tabjour4[1]['fin'])->format('%h');
                    for($j=1;$j<=$duree1;$j++){
                        array_push($tabret4,array('intitule' => $tabjour4[0]['intitule'], 'intervenant' => $tabjour4[0]['nom'].' '.$tabjour4[0]['prenom']));
                    }
                    for($j=1;$j<=2+(4-$duree1);$j++){
                        array_push($tabret4,array('intitule' => ' ', 'intervenant' => ' '));
                    }
                    for($j=1;$j<=$duree2;$j++){
                        array_push($tabret4,array('intitule' => $tabjour4[1]['intitule'], 'intervenant' =>$tabjour4[1]['nom'].' '.$tabjour4[1]['prenom']));
                    }
                    for($j=1;$j<=3-$duree2;$j++){
                        array_push($tabret4,array('intitule' => ' ', 'intervenant' => ' '));
                    }
                } elseif (count($tabjour4)==1){
                    $duree1 = date_diff($tabjour4[0]['debut'],$tabjour4[0]['fin'])->format('%h');
                    $this->console_log($tabjour4[0]['debut']->format('H'));
                    if($tabjour4[0]['debut']->format('H') == '09'){
                        $this->console_log('il est 9h');
                        for($j=1;$j<=$duree1;$j++){
                            array_push($tabret4,array('intitule' => $tabjour4[0]['intitule'], 'intervenant' => $tabjour4[0]['nom'].' '.$tabjour4[0]['prenom']));
                        }
                        for($j=1;$j<=5+(4-$duree1);$j++){
                            array_push($tabret4,array('intitule' => ' ', 'intervenant' => ' '));
                        }
                    }elseif ($tabjour4[0]['debut']->format('H') == '15'){
                        $this->console_log('il est 15h');
                        for($j=1;$j<=6;$j++){
                            array_push($tabret4,array('intitule' => ' ', 'intervenant' => ' '));
                        }
                        for($j=1;$j<=$duree1;$j++){
                            array_push($tabret4,array('intitule' => $tabjour4[0]['intitule'], 'intervenant' => $tabjour4[0]['nom'].' '.$tabjour4[0]['prenom']));
                        }
                        for($j=1;$j<=3-$duree1;$j++){
                            array_push($tabret4,array('intitule' => ' ', 'intervenant' => ' '));
                        }
                    }
                } else {
                    for($j=1;$j<=9;$j++){
                        array_push($tabret4,array('intitule' => ' ', 'intervenant' => ' '));
                    }
                }
            }

            $tabjour5 = $tab[4];
            $tabret5 = array();
            for ($i=0; $i<=count($tabjour5); $i++) {
                if (count($tabjour5)==2)
                {
                    $duree1 = date_diff($tabjour5[0]['debut'],$tabjour5[0]['fin'])->format('%h');
                    $duree2 = date_diff($tabjour5[1]['debut'],$tabjour5[1]['fin'])->format('%h');
                    for($j=1;$j<=$duree1;$j++){
                        array_push($tabret5,array('intitule' => $tabjour5[0]['intitule'], 'intervenant' => $tabjour5[0]['nom'].' '.$tabjour5[0]['prenom']));
                    }
                    for($j=1;$j<=2+(4-$duree1);$j++){
                        array_push($tabret5,array('intitule' => ' ', 'intervenant' => ' '));
                    }
                    for($j=1;$j<=$duree2;$j++){
                        array_push($tabret5,array('intitule' => $tabjour5[1]['intitule'], 'intervenant' =>$tabjour5[1]['nom'].' '.$tabjour5[1]['prenom']));
                    }
                    for($j=1;$j<=3-$duree2;$j++){
                        array_push($tabret5,array('intitule' => ' ', 'intervenant' => ' '));
                    }
                } elseif (count($tabjour5)==1){
                    $duree1 = date_diff($tabjour5[0]['debut'],$tabjour5[0]['fin'])->format('%h');
                    $this->console_log($tabjour5[0]['debut']->format('H'));
                    if($tabjour5[0]['debut']->format('H') == '09'){
                        $this->console_log('il est 9h');
                        for($j=1;$j<=$duree1;$j++){
                            array_push($tabret5,array('intitule' => $tabjour5[0]['intitule'], 'intervenant' => $tabjour5[0]['nom'].' '.$tabjour5[0]['prenom']));
                        }
                        for($j=1;$j<=5+(4-$duree1);$j++){
                            array_push($tabret5,array('intitule' => ' ', 'intervenant' => ' '));
                        }
                    }elseif ($tabjour5[0]['debut']->format('H') == '15'){
                        $this->console_log('il est 15h');
                        for($j=1;$j<=6;$j++){
                            array_push($tabret5,array('intitule' => ' ', 'intervenant' => ' '));
                        }
                        for($j=1;$j<=$duree1;$j++){
                            array_push($tabret5,array('intitule' => $tabjour5[0]['intitule'], 'intervenant' => $tabjour5[0]['nom'].' '.$tabjour5[0]['prenom']));
                        }
                        for($j=1;$j<=3-$duree1;$j++){
                            array_push($tabret5,array('intitule' => ' ', 'intervenant' => ' '));
                        }
                    }
                } else {
                    for($j=1;$j<=9;$j++){
                        array_push($tabret5,array('intitule' => ' ', 'intervenant' => ' '));
                    }
                }
            }
            return array($tabRet1,$tabRet2,$tabret3,$tabret4,$tabret5);
        }

    function console_log( $data ){
        echo '<script>';
        echo 'console.log('. json_encode( $data ) .')';
        echo '</script>';
    }



}
