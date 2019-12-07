<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Entity\Intervenant;
use App\Entity\Matiere;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManager;

class CoursController extends AbstractController
{
    public $routeDetailSem = 'creneauDetailSemaine';
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->entityManager = $em;

    }

    /**
     * @Route("/week/{noann}/{nosem}", name="creneauDetailSemaine")
     */
    function afficherCreneauSemaine($noann, $nosem)
    {
        if ($nosem <= 53 && $nosem > 1) {
            $jour1 = mktime(0, 0, 0, (new DateTime())->setISODate($noann, $nosem)->format('m'), (new DateTime())->setISODate($noann, $nosem)->format('d'), $noann);
            $jour2 = mktime(0, 0, 0, (new DateTime())->setISODate($noann, $nosem)->format('m'), (new DateTime())->setISODate($noann, $nosem)->format('d') + 1, $noann);
            $jour3 = mktime(0, 0, 0, (new DateTime())->setISODate($noann, $nosem)->format('m'), (new DateTime())->setISODate($noann, $nosem)->format('d') + 2, $noann);
            $jour4 = mktime(0, 0, 0, (new DateTime())->setISODate($noann, $nosem)->format('m'), (new DateTime())->setISODate($noann, $nosem)->format('d') + 3, $noann);
            $jour5 = mktime(0, 0, 0, (new DateTime())->setISODate($noann, $nosem)->format('m'), (new DateTime())->setISODate($noann, $nosem)->format('d') + 4, $noann);

            $tabCours = $this->initTabs($this->setTabs($jour1, $jour2, $jour3, $jour4, $jour5));
            //$tab1 = $tabCours[0];

            $tab = array(
                array($this->deterJour(date("N", $jour1)),
                    date("j", $jour1),
                    $this->deterMois(date("m", $jour1))),
                array($this->deterJour(date("N", $jour2)),
                    date("j", $jour2),
                    $this->deterMois(date("m", $jour2))),
                array($this->deterJour(date("N", $jour3)),
                    date("j", $jour3),
                    $this->deterMois(date("m", $jour3))),
                array($this->deterJour(date("N", $jour4)),
                    date("j", $jour4),
                    $this->deterMois(date("m", $jour4))),
                array($this->deterJour(date("N", $jour5)),
                    date("j", $jour5),
                    $this->deterMois(date("m", $jour5)))
            );
            return $this->render('/cours/week.html.twig', ['tab' => $tab, 'noSem' => $nosem, 'noAnn' => $noann, 'cours' => $tabCours]);
        } elseif ($nosem == 1 || $nosem == 54) {
            if ($nosem == 54) {
                return $this->redirectToRoute("creneauDetailSemaine", array('nosem' => 2, 'noann' => $noann + 1));
            }
            if ($nosem == 1) {
                return $this->redirectToRoute("creneauDetailSemaine", array('nosem' => 53, 'noann' => $noann - 1));
            }
        } else {
            throw $this->createNotFoundException("Ce numéro de semaine n'existe pas.");
        }
    }

    /**
 * @Route("/month")
 */
    function afficherMois()
    {
        $nomon = date("m");
        $noann = date("Y");
        return $this->redirectToRoute("creneauDetailMois", array('nomon' => $nomon, 'noann' => $noann));
    }

    /**
     * @Route("/month/{noann}/{nomon}", name="creneauDetailMois")
     */
    function afficherCreneauMois($noann, $nomon){
        if ($nomon >=1 && $nomon<=12) {
            $jour = mktime(0, 0, 0, $nomon, 1, $noann);
            $tabJours = $this->setDaysMonth($nomon, $noann);
            $this->console_log($tabJours);
            return $this->render('/cours/month.html.twig', ['tabJours' => $tabJours, 'nomon' => $nomon, 'noann' => $noann]);
        }elseif($nomon == 13 || $nomon == 0) {
            if($nomon == 13){
                return $this->redirectToRoute("creneauDetailMois", array('nomon' => 1, 'noann' => $noann + 1));
            }
            if($nomon == 0){
                return $this->redirectToRoute("creneauDetailMois", array('nomon' => 12, 'noann' => $noann - 1));
            }
        }else{
            throw $this->createNotFoundException("Ce numéro de semaine n'existe pas.");
        }
    }

    /**
     * @Route("/year")
     */
    function afficherAnnee()
    {
        $noann = date("Y");
        return $this->redirectToRoute("creneauDetailAnnee", array('noann' => $noann));
    }

    /**
     * @Route("/year/{noann}", name="creneauDetailAnnee")
     */
    function afficherCreneauAnnee($noann){
        $this->console_log($noann);
        $bisex = date("L", mktime(0, 0, 0, 1, 1, $noann));
        $tabCours = $this->setTabYear($noann);
        return $this->render('/cours/year.html.twig',array('bisex' => $bisex, 'noann'=> $noann, 'tabCours'=> $tabCours));
    }

    /**
     * @Route("/week")
     */
    function afficherCreneau()
    {
        $nosem = date("W");
        $noann = date("Y");
        return $this->redirectToRoute("creneauDetailSemaine", array('nosem' => $nosem, 'noann' => $noann));
    }

    function deterJour($numjour)
    {
        switch ($numjour) {
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


    function setTabs($jour1, $jour2, $jour3, $jour4, $jour5)
    {
        $tabjour = array($jour1, $jour2, $jour3, $jour4, $jour5);
        $tabRet = array();
        for ($i = 0; $i <= 4; $i++) {
            $query1 = $this->entityManager->createQuery('SELECT m.intitule AS intitule, i.nom as nom, i.prenom as prenom, c.debut as debut, c.fin as fin, m.isSpecialite as spe FROM App\Entity\Matiere m, App\Entity\Intervenant i, App\Entity\Cours c
                        WHERE m.id = c.fk_matiere_id AND i.id = c.fk_intervenant_id and c.fk_intervenant_id = 1 and c.debut >= ' . date('Ymd', $tabjour[$i]) . ' AND c.debut < ' . date('Ymd', $tabjour[$i] + 86400) . ' ORDER BY c.debut');
            array_push($tabRet, $query1->getResult());
        }
        return $tabRet;
    }

    function initTabs($tab)
    {
        $tabRet = array(array(), array(), array(), array(), array());
        for ($m = 0; $m < count($tab); $m++) {
            $tabJour = $tab[$m];

            for ($i = 0; $i <= count($tabJour); $i++) {
                if (count($tabJour) == 2) {
                    $duree1 = date_diff($tabJour[0]['debut'], $tabJour[0]['fin'])->format('%h');
                    $duree2 = date_diff($tabJour[1]['debut'], $tabJour[1]['fin'])->format('%h');
                    for ($j = 1; $j <= $duree1; $j++) {
                        array_push($tabRet[$m], array('intitule' => $tabJour[0]['intitule'], 'intervenant' => $tabJour[0]['nom'] . ' ' . $tabJour[0]['prenom']));
                    }
                    for ($j = 1; $j <= 2 + (4 - $duree1); $j++) {
                        array_push($tabRet[$m], array('intitule' => ' ', 'intervenant' => ' '));
                    }
                    for ($j = 1; $j <= $duree2; $j++) {
                        array_push($tabRet[$m], array('intitule' => $tabJour[1]['intitule'], 'intervenant' => $tabJour[1]['nom'] . ' ' . $tabJour[1]['prenom']));
                    }
                    for ($j = 1; $j <= 3 - $duree2; $j++) {
                        array_push($tabRet[$m], array('intitule' => ' ', 'intervenant' => ' '));
                    }
                } elseif (count($tabJour) == 1) {
                    $duree1 = date_diff($tabJour[0]['debut'], $tabJour[0]['fin'])->format('%h');
                    $this->console_log($tabJour[0]['debut']->format('H'));
                    if ($tabJour[0]['debut']->format('H') == '09') {
                        for ($j = 1; $j <= $duree1; $j++) {
                            array_push($tabRet[$m], array('intitule' => $tabJour[0]['intitule'], 'intervenant' => $tabJour[0]['nom'] . ' ' . $tabJour[0]['prenom']));
                        }
                        for ($j = 1; $j <= 5 + (4 - $duree1); $j++) {
                            array_push($tabRet[$m], array('intitule' => ' ', 'intervenant' => ' '));
                        }
                    } elseif ($tabJour[0]['debut']->format('H') == '15') {
                        for ($j = 1; $j <= 6; $j++) {
                            array_push($tabRet[$m], array('intitule' => ' ', 'intervenant' => ' '));
                        }
                        for ($j = 1; $j <= $duree1; $j++) {
                            array_push($tabRet[$m], array('intitule' => $tabJour[0]['intitule'], 'intervenant' => $tabJour[0]['nom'] . ' ' . $tabJour[0]['prenom']));
                        }
                        for ($j = 1; $j <= 3 - $duree1; $j++) {
                            array_push($tabRet[$m], array('intitule' => ' ', 'intervenant' => ' '));
                        }
                    }
                } else {
                    for ($j = 1; $j <= 9; $j++) {
                        array_push($tabRet[$m], array('intitule' => ' ', 'intervenant' => ' '));
                    }
                }
            }
        }

        return $tabRet;
    }

    function console_log($data)
    {
        echo '<script>';
        echo 'console.log(' . json_encode($data) . ')';
        echo '</script>';
    }

    function weeksPerMonth($month, $year) {
        $day = mktime(1, 1, 1, $month, 1, $year);
        $nday = mktime(1, 1, 1, $month, date('t', $day), $year);
        $week = date('W', $day);
        $nweek = date('W', $nday);
        $lweek = date('W', mktime(1, 1, 1, 12, 28, $year));
        if ($nweek > $week) $res = $nweek - $week;
        else if ($lweek > $week) $res = $nweek + $lweek - $week;
        else $res = (int)$nweek;
        return $res + 1;
    }

    function setDaysMonth($nomon, $noann){
        $jour = mktime(0, 0, 0, $nomon, 1, $noann);
        $nb = 0;
        $jourASauter = 0;
        $sem = date("W", $jour);
        $nbSem = $this->weeksPerMonth($nomon, $noann);
        $tabJours = array(array(), array(), array(), array(), array());
        if (date("N", $jour) < 6) {
            array_push($tabJours[$nb], array("Semaine " . $sem," "));
        } else {
            switch (date("N", $jour)) {
                case 6:
                    $jour += 86400 * 2;
                    $sem++;
                    array_push($tabJours[$nb], array("Semaine " . $sem," "));
                    $jourASauter = 2;
                    break;
                case 7:
                    $jour += 86400;
                    $sem++;
                    array_push($tabJours[$nb], array("Semaine " . $sem," "));
                    $jourASauter = 1;
                    break;
            }
        }
        for ($j = 1; $j < date("N", $jour); $j++) {
            array_push($tabJours[$nb], array(" "," "));
        }
        $nbjour = date("t", $jour);
        for ($j = 1; $j <= $nbjour - $jourASauter; $j++) {
            if (date("W", $jour) != $sem) {
                $nb = $nb + 1;
                $sem = date("W", $jour);
                array_push($tabJours[$nb], array("Semaine " . $sem," "));
            }
            if (date("N", $jour) < 6) {
                //array_push($tabJours[$nb], date("j", $jour) . " " . $this->deterMois(date("n", $jour)) . " " . date("Y", $jour));
                $query1 = $this->entityManager->createQuery('SELECT m.intitule AS intitule FROM App\Entity\Matiere m, App\Entity\Cours c
                        WHERE m.id = c.fk_matiere_id and (c.fk_intervenant_id = 3 or c.fk_intervenant_id = 1) and c.debut >= ' . date('Ymd', $jour) . ' AND c.debut < ' . date('Ymd', $jour + 86400) . ' ORDER BY c.debut');
                $tabRetQuery = $query1->getResult();
                if (count($tabRetQuery) >0) {
                    if ($tabRetQuery[0]['intitule'] == "ENTREPRISE") {
                        array_push($tabJours[$nb], array(date("j", $jour) . " " . $this->deterMois(date("n", $jour)) . " " . date("Y", $jour), "E"));
                    } else {
                        array_push($tabJours[$nb], array(date("j", $jour) . " " . $this->deterMois(date("n", $jour)) . " " . date("Y", $jour), "C"));
                    }
                }else {
                    array_push($tabJours[$nb], array(date("j", $jour) . " " . $this->deterMois(date("n", $jour)) . " " . date("Y", $jour), " "));
                }
            }
            $jour += 86400;
        }
        for ($nbTab = $nb; $nbTab < 5; $nbTab++) {
            for ($posTab = count($tabJours[$nbTab]); $posTab < 6; $posTab++) {
                array_push($tabJours[$nbTab], array(" "," "));
            }
        }
        return $tabJours;
    }

    function setTabYear($noann){
        $tabRet = array(array(), array(), array(), array(), array(),array(),array(),array(),array(),array(),array(),array());
        for($i=0;$i<12;$i++){
            $jour = mktime(0, 0, 0, $i+1, 1, $noann);
            for($j=1;$j<=date("t",$jour);$j++){
                if (date("N", $jour) < 6) {
                    $query1 = $this->entityManager->createQuery('SELECT m.intitule AS intitule FROM App\Entity\Matiere m, App\Entity\Cours c
                        WHERE m.id = c.fk_matiere_id and (c.fk_intervenant_id = 3 or c.fk_intervenant_id = 1) and c.debut >= ' . date('Ymd', $jour) . ' AND c.debut < ' . date('Ymd', $jour + 86400) . ' ORDER BY c.debut');
                    $tabRetQuery = $query1->getResult();
                    if (count($tabRetQuery) >0) {
                        if ($tabRetQuery[0]['intitule'] == "ENTREPRISE") {
                            array_push($tabRet[$i],  "ENTREPRISE");
                        } else {
                            array_push($tabRet[$i], "ECOLE");
                        }
                    }else {
                        array_push($tabRet[$i], " ");
                    }
                }else {
                    array_push($tabRet[$i], " ");
                }
                $jour += 86400;
            }
        }
        return $tabRet;
    }




}
