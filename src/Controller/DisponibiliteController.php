<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DisponibiliteController extends AbstractController
{
    /**
     * @Route("/disponibilite", name="disponibilite")
     */
    public function index()
    {
        return $this->render('disponibilite/index.html.twig', [
            'controller_name' => 'DisponibiliteController',
        ]);
    }

    /**
     * @Route("/disponibilite/add", name="addDisponibilite")
     */
    function setDispo($jour1, $jour2, $jour3, $jour4, $jour5)
    {
        $tabjour = array($jour1, $jour2, $jour3, $jour4, $jour5);
        $tabRet = array();
        $user = new Utilisateurs();
        $user = $this->getUser();
        $idIntervenant = $user->getFkIntervenantId();
        $isAdmin = $user->getIsAdmin();
        $this->console_log("ID :".$idIntervenant);
        for ($i = 0; $i <= 4; $i++) {
            if ($isAdmin == 1)
            {
                $query1 = $this->entityManager->createQuery('SELECT m.intitule AS intitule, i.nom as nom, i.prenom as prenom, c.debut as debut, c.fin as fin, m.isSpecialite as spe FROM App\Entity\Matiere m, App\Entity\Intervenant i, App\Entity\Cours c
                        WHERE m.id = c.fk_matiere_id AND i.id = c.fk_intervenant_id and c.debut >= ' . date('Ymd', $tabjour[$i]) . ' AND c.debut < ' . date('Ymd', $tabjour[$i] + 86400) . ' ORDER BY c.debut');
            }
            else {
                $query1 = $this->entityManager->createQuery('SELECT m.intitule AS intitule, i.nom as nom, i.prenom as prenom, c.debut as debut, c.fin as fin, m.isSpecialite as spe FROM App\Entity\Matiere m, App\Entity\Intervenant i, App\Entity\Cours c
                        WHERE m.id = c.fk_matiere_id AND i.id = c.fk_intervenant_id and c.fk_intervenant_id = '.$idIntervenant.' and c.debut >= ' . date('Ymd', $tabjour[$i]) . ' AND c.debut < ' . date('Ymd', $tabjour[$i] + 86400) . ' ORDER BY c.debut');
            }
            array_push($tabRet, $query1->getResult());
        }
        return $tabRet;
    }
}
