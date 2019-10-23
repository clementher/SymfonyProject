<?php

namespace App\Controller;

use App\Entity\Intervenant;
use App\Entity\Matiere;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\Routing\Annotation\Route;

class CoursController extends AbstractController
{
    public $routeDetailSem = 'creneauDetailSemaine';

    /**
     * @Route("/week/{noann}/{nosem}", name="creneauDetailSemaine")
     */
    function afficherCreneauSemaine($noann,$nosem){
        if ($nosem <=53 && $nosem >1) {
            $tab = array(
                array($this->deterJour(date("N", mktime(0, 0, 0, (new DateTime())->setISODate($noann, $nosem)->format('m'), (new DateTime())->setISODate($noann, $nosem)->format('d'), $noann))),
                    date("j", mktime(0, 0, 0, (new DateTime())->setISODate($noann, $nosem)->format('m'), (new DateTime())->setISODate($noann, $nosem)->format('d'), $noann)),
                    $this->deterMois(date("m", mktime(0, 0, 0, (new DateTime())->setISODate($noann, $nosem)->format('m'), (new DateTime())->setISODate($noann, $nosem)->format('d'), $noann)))),
                array($this->deterJour(date("N", mktime(0, 0, 0, (new DateTime())->setISODate($noann, $nosem)->format('m'), (new DateTime())->setISODate($noann, $nosem)->format('d') + 1, $noann))),
                    date("j", mktime(0, 0, 0, (new DateTime())->setISODate($noann, $nosem)->format('m'), (new DateTime())->setISODate($noann, $nosem)->format('d') + 1, $noann)),
                    $this->deterMois(date("m", mktime(0, 0, 0, (new DateTime())->setISODate($noann, $nosem)->format('m'), (new DateTime())->setISODate($noann, $nosem)->format('d') + 1, $noann)))),
                array($this->deterJour(date("N", mktime(0, 0, 0, (new DateTime())->setISODate($noann, $nosem)->format('m'), (new DateTime())->setISODate($noann, $nosem)->format('d') + 2, $noann))),
                    date("j", mktime(0, 0, 0, (new DateTime())->setISODate($noann, $nosem)->format('m'), (new DateTime())->setISODate($noann, $nosem)->format('d') + 2, $noann)),
                    $this->deterMois(date("m", mktime(0, 0, 0, (new DateTime())->setISODate($noann, $nosem)->format('m'), (new DateTime())->setISODate($noann, $nosem)->format('d') + 2, $noann)))),
                array($this->deterJour(date("N", mktime(0, 0, 0, (new DateTime())->setISODate($noann, $nosem)->format('m'), (new DateTime())->setISODate($noann, $nosem)->format('d') + 3, $noann))),
                    date("j", mktime(0, 0, 0, (new DateTime())->setISODate($noann, $nosem)->format('m'), (new DateTime())->setISODate($noann, $nosem)->format('d') + 3, $noann)),
                    $this->deterMois(date("m", mktime(0, 0, 0, (new DateTime())->setISODate($noann, $nosem)->format('m'), (new DateTime())->setISODate($noann, $nosem)->format('d') + 3, $noann)))),
                array($this->deterJour(date("N", mktime(0, 0, 0, (new DateTime())->setISODate($noann, $nosem)->format('m'), (new DateTime())->setISODate($noann, $nosem)->format('d') + 4, $noann))),
                    date("j", mktime(0, 0, 0, (new DateTime())->setISODate($noann, $nosem)->format('m'), (new DateTime())->setISODate($noann, $nosem)->format('d') + 4, $noann)),
                    $this->deterMois(date("m", mktime(0, 0, 0, (new DateTime())->setISODate($noann, $nosem)->format('m'), (new DateTime())->setISODate($noann, $nosem)->format('d') + 4, $noann))))
            );
            return $this->render('/cours/month.html.twig', ['tab' => $tab, 'noSem' => $nosem,'noAnn' => $noann]);
        }
        elseif ($nosem = 1 ||$nosem = 54){
            if ($nosem =54) {
                return $this->redirectToRoute("creneauDetailSemaine",array('nosem'=>2, 'noann' =>$noann+1));
            }
            if ($nosem = 1) {
                return $this->redirectToRoute("creneauDetailSemaine", array('nosem' => 53, 'noann' => $noann - 1));
            }
        }
        else {
            throw $this->createNotFoundException("Ce numéro de semaine n'existe pas.");
        }
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

    function deterMois($nummois){
        switch ($nummois){
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


}
