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
            return $this->render('/cours/week.html.twig', ['tab' => $tab, 'noSem' => $nosem, 'noAnn' => $noann, 'compteur' => $tabCours]);
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
    function afficherJours()
    {
        return $this->render('/cours/month.html.twig');
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


}
