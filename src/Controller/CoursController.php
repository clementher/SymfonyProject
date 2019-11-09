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

            $tabCours = $this->setTabs($jour1, $jour2, $jour3, $jour4, $jour5);
            $tab1 = $tabCours[0];

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
            return $this->render('/cours/month.html.twig', ['tab' => $tab, 'noSem' => $nosem,'noAnn' => $noann, 'compteur' => $tab1]);
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

            $query1 = $this->entityManager -> createQuery('SELECT m.intitule AS intitule, i.nom as nom, i.prenom as prenom, c.debut as debut, c.fin as fin FROM App\Entity\Matiere m, App\Entity\Intervenant i, App\Entity\Cours c
                        WHERE m.id = c.fk_matiere_id AND i.id = c.fk_intervenant_id and c.fk_intervenant_id = 1 and c.debut >= '.date('Ymd', $jour1).' AND c.debut < '.date('Ymd',$jour2).' ORDER BY c.debut');
            /**$this->entityManager ->createQueryBuilder()->select('c')
                -> from(Cours::class, 'c') -> where('c.fk_intervenant_id = 1') -> andWhere('c.debut >= '.date('Ymd', $jour1)) -> andWhere('c.debut < '.date('Ymd',$jour2))
                -> getQuery();**/

            $tab1 = $query1->getResult();

            $query2 = $this->entityManager -> createQuery('SELECT m.intitule AS intitule, i.nom as nom, i.prenom as prenom, c.debut as debut, c.fin as fin FROM App\Entity\Matiere m, App\Entity\Intervenant i, App\Entity\Cours c
                        WHERE m.id = c.fk_matiere_id AND i.id = c.fk_intervenant_id and c.fk_intervenant_id = 1 and c.debut >= '.date('Ymd', $jour2).' AND c.debut < '.date('Ymd',$jour3).' ORDER BY c.debut');
                /**$this->entityManager ->createQueryBuilder()->select('c')
                -> from(Cours::class, 'c') -> where('c.fk_intervenant_id = 1') -> andWhere('c.debut > :date1') -> andWhere('c.debut < :date2')
                -> setParameter(':date1',date('Y-m-d', $jour2)) -> setParameter(':date2', date('Y-m-d',$jour3)) -> getQuery();**/
            $tab2 = $query2->getResult();

            $query3 = $this->entityManager -> createQuery('SELECT m.intitule AS intitule, i.nom as nom, i.prenom as prenom, c.debut as debut, c.fin as fin FROM App\Entity\Matiere m, App\Entity\Intervenant i, App\Entity\Cours c
                        WHERE m.id = c.fk_matiere_id AND i.id = c.fk_intervenant_id and c.fk_intervenant_id = 1 and c.debut >= '.date('Ymd', $jour3).' AND c.debut < '.date('Ymd',$jour4).' ORDER BY c.debut');
                /**$this->entityManager ->createQueryBuilder()->select('c')
                -> from(Cours::class, 'c') -> where('c.fk_intervenant_id = 1') -> andWhere('c.debut > :date1') -> andWhere('c.debut < :date2')
                -> setParameter(':date1', date('Y-m-d',$jour3)) -> setParameter(':date2', date('Y-m-d',$jour4)) -> getQuery();**/
            $tab3 = $query3->getResult();

            $query4 =  $this->entityManager -> createQuery('SELECT m.intitule AS intitule, i.nom as nom, i.prenom as prenom, c.debut as debut, c.fin as fin FROM App\Entity\Matiere m, App\Entity\Intervenant i, App\Entity\Cours c
                        WHERE m.id = c.fk_matiere_id AND i.id = c.fk_intervenant_id and c.fk_intervenant_id = 1 and c.debut >= '.date('Ymd', $jour4).' AND c.debut < '.date('Ymd',$jour5).' ORDER BY c.debut');
                /**$this->entityManager ->createQueryBuilder()->select('c')
                -> from(Cours::class, 'c') -> where('c.fk_intervenant_id = 1') -> andWhere('c.debut > :date1') -> andWhere('c.debut < :date2')
                -> setParameter(':date1', date('Y-m-d',$jour4)) -> setParameter(':date2', date('Y-m-d',$jour5)) -> getQuery();**/
            $tab4 = $query4->getResult();

            $query5 = $this->entityManager -> createQuery('SELECT m.intitule AS intitule, i.nom as nom, i.prenom as prenom, c.debut as debut, c.fin as fin FROM App\Entity\Matiere m, App\Entity\Intervenant i, App\Entity\Cours c
                        WHERE m.id = c.fk_matiere_id AND i.id = c.fk_intervenant_id and c.fk_intervenant_id = 1 and c.debut >= '.date('Ymd', $jour5).' AND c.debut < '.date('Ymd',$jour5 + 86400).' ORDER BY c.debut');
                /**$this->entityManager ->createQueryBuilder()->select('c')
                -> from(Cours::class, 'c') -> where('c.fk_intervenant_id = 1') -> andWhere('c.debut >'. date('Y-m-d',$jour5)) -> andWhere('c.debut <'.date('Y-m-d',$jour5+86400))
                -> getQuery();**/
            $tab5 = $query5->getResult();

            return array($tab1, $tab2, $tab3, $tab4, $tab5);
        }

        function initTabs($tab){

        }



}
