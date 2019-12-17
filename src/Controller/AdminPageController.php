<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Entity\Intervenant;
use App\Entity\Matiere;
use App\Entity\Utilisateurs;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminPageController extends AbstractController
{
    private $passwordEncoder;

    /**
     * @Route("/admin/intervenant", name="app_admin_intervenant")
     */
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
        $user = new Utilisateurs();
        $userForm = $this->createUserform($user);
        $userForm->handleRequest($request);

        #Form User
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $isUserValid = $this->getUserByEmail($user->getEmail());
            $isIntervenantValid = $this->getIntervenantByEmail($user->getEmail());
            if (!$isUserValid || !$isIntervenantValid) {
                $id = $this->createIntervenant($user);
                $this->createUser($user, $id);
                $this->addFlash('success', "L'utilisateur a été créé");
                unset($user);
                unset($userForm);
                $user = new Utilisateurs();
                $userForm = $this->createUserform($user);
            } else {
                $userForm->addError(new FormError("L'utilisateur existe déjà"));
            }
        }
        $users = $this->getAllUsers();

        return $this->render('admin_user.html.twig', [
            'userForm' => $userForm->createView(),
            'users' => $users,
        ]);
    }

    /**
     * @Route("/admin/matiere", name="app_admin_matiere")
     */
    public function matiere(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
        $matiere = new Matiere();
        $matiereForm = $this->createMatiereform($matiere);
        $matiereForm->handleRequest($request);

        #Form Matiere
        if ($matiereForm->isSubmitted() && $matiereForm->isValid()) {
            $idInter = isset($_POST['intervenant']) ? $_POST['intervenant'] : null;
            if ($idInter) {
            $this->createMatiere($matiere, $idInter);
            $this->addFlash('success', "La matière a été créée");
            unset($matiere);
            unset($matiereForm);
            $matiere = new Matiere();
            $matiereForm = $this->createMatiereform($matiere);
            } else {
                $matiereForm->addError(new FormError("L'intervenant n'existe pas"));
            }
        }
        $matieres = $this->getAllMatieres();
        $inters = $this->getAllIntervenants();

        return $this->render('admin_matiere.html.twig', [
            'matiereForm' => $matiereForm->createView(),
            'matieres' => $matieres,
            'inters' => $inters
        ]);

    }

    /**
     * @Route("/admin/cour", name="app_admin_cour")
     */
    public function Cour(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
        $cours = new Cours();
        $coursForm = $this->createCoursform($cours);
        $coursForm->handleRequest($request);

        #Form Cours
        if ($coursForm->isSubmitted() && $coursForm->isValid()) {
            $idInter = isset($_POST['intervenant']) ? $_POST['intervenant'] : null;
            if ($idInter) {
                $this->createCours($cours, $idInter);
                $this->addFlash('success', "Le cour a été créé");
                unset($cours);
                unset($coursForm);
                $cours = new Cours();
                $coursForm = $this->createCoursform($cours);
            } else {
                $coursForm->addError(new FormError("L'intervenant n'existe déjà"));
            }

        }
        $inters = $this->getAllIntervenants();

        return $this->render('admin_cour.html.twig', [
            'coursForm' => $coursForm->createView(),
            'inters' => $inters
        ]);
    }

    public function createUserform(Utilisateurs $user)
    {
        $userForm = $this->createFormBuilder($user)
            ->add('nom')
            ->add('prenom')
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class)
            ->add('specialiteprofessionnelle')
            ->getForm();
        return $userForm;
    }

    public function createCoursform(Cours $cours)
    {
        $coursForm = $this->createFormBuilder($cours)
            ->add('debut')
            ->add('fin')
            ->getForm();
        return $coursForm;
    }

    public function createMatiereform(Matiere $matiere)
    {
        $matiereForm = $this->createFormBuilder($matiere)
            ->add('intitule')
            ->add('duree')
            ->add('isSpecialite')
            ->getForm();
        return $matiereForm;
    }

    public function createIntervenant(Utilisateurs $user)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $intervenant = new Intervenant();
        $intervenant->setNom($user->getNom());
        $intervenant->setPrenom($user->getPrenom());
        $intervenant->setEmail($user->getEmail());
        $intervenant->setSpecialiteprofessionnelle($user->getSpecialiteprofessionnelle());
        //informer Doctrine qu'on peut persister ces données
        $entityManager->persist($intervenant);
        //Executer la requête
        $entityManager->flush();
        return $intervenant->getId();
    }

    public function createUser(Utilisateurs $user, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));
        $user->setFkIntervenantId($id);
        $user->setIsAdmin("0");
        //informer Doctrine qu'on peut persister ces données
        $entityManager->persist($user);
        //Executer la requête
        $entityManager->flush();
    }

    public function createCours(Cours $cours, $idInter)
    {
        $intervenant = $this->getDoctrine()
            ->getRepository(Intervenant::class)
            ->find($idInter);
        $cours->setFkIntervenantId($intervenant);
        $entityManager = $this->getDoctrine()->getManager();
        //informer Doctrine qu'on peut persister ces données
        $entityManager->persist($cours);
        //Executer la requête
        $entityManager->flush();
    }

    public function createMatiere(Matiere $matiere,$idInter)
    {
        $intervenant = $this->getDoctrine()
            ->getRepository(Intervenant::class)
            ->find($idInter);
        $matiere->setFkIntervenant($intervenant);
        $entityManager = $this->getDoctrine()->getManager();
        //informer Doctrine qu'on peut persister ces données
        $entityManager->persist($matiere);
        //Executer la requête
        $entityManager->flush();
    }

    public function getIntervenantByEmail($email)
    {
        $intervenant = $this->getDoctrine()
            ->getRepository(Intervenant::class)
            ->findOneBy(['email' => $email]);
        if ($intervenant) {
            return true;
        } else {
            return false;
        }
    }

    public function getUserByEmail($email)
    {
        $user = $this->getDoctrine()
            ->getRepository(Utilisateurs::class)
            ->findOneBy(['email' => $email]);
        if ($user) {
            return true;
        } else {
            return false;
        }
    }

    public function getAllUsers()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository(Utilisateurs::class)
            ->findAll();
        return $users;
    }

    public function getAllIntervenants()
    {
        $em = $this->getDoctrine()->getManager();
        $inter = $em->getRepository(Intervenant::class)
            ->findAll();
        return $inter;
    }

    public function getAllMatieres()
    {
        $em = $this->getDoctrine()->getManager();
        $matieres = $em->getRepository(Matiere::class)
            ->findAll();
        return $matieres;
    }

    /**
     * @Route("/delete_User/{id}", name="app_delete_user")
     */
    public function deleteUser($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(Utilisateurs::class)
            ->find($id);
        if ($user) {
            $em->remove($user);
            $em->flush();
            $this->addFlash('success', "L'utilisateur a été supprimé");
        }
        return $this->redirect($this->generateUrl('app_admin_intervenant'));
    }

    /**
     * @Route("/delete_matiere/{id}", name="app_delete_matiere")
     */
    public function deleteMatiere($id)
    {
        $em = $this->getDoctrine()->getManager();
        $matiere = $em->getRepository(Matiere::class)
            ->find($id);
        if ($matiere) {
            $em->remove($matiere);
            $em->flush();
            $this->addFlash('success', "La matière a été supprimée");
        }
        return $this->redirect($this->generateUrl('app_admin_matiere'));
    }
}