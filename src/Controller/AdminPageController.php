<?php
namespace App\Controller;
use App\Entity\Intervenant;
use App\Entity\Utilisateurs;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Tests\Encoder\PasswordEncoder;
class AdminPageController extends AbstractController
{
    private $passwordEncoder;
    private $user;
    private $form;
    /**
     * @Route("/admin", name="app_admin")
     */
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->form = $this->createformUtilisateur();
        $this->form->handleRequest($request);
        if ($this->form->isSubmitted() && $this->form->isValid()) {
            $isUserValid = $this->getUserByEmail($this->user->getEmail());
            $isIntervenantValid = $this->getIntervenantByEmail($this->user->getEmail());
            if(!$isUserValid || !$isIntervenantValid) {
                $id = $this->createIntervenant($this->user);
                $this->createUser($this->user, $id);
                $this->addFlash('success', "L'utilisateur a été créé");
                unset($this->user);
                unset($form);
                $this->form = $this->createformUtilisateur();
            }else{
                $this->form->addError(new FormError("L'utilisateur existe déjà"));
            }
        }
        $users = $this->getAllUsers();
        return $this->render('admin.html.twig', [
            'formUser' => $this->form->createView(),
            'users' => $users
        ]);
    }
    public function createformUtilisateur(){
        $this->user = new Utilisateurs();
        $this->form = $this->createFormBuilder($this->user)
            ->add('nom')
            ->add('prenom')
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class)
            ->add('specialiteprofessionnelle')
            ->getForm();
        return $this->form;
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
    public function getAllUsers(){
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository(Utilisateurs::class)
            ->findAll();
        return $users;
    }
    /**
     * @Route("/delete/{id}", name="app_delete")
     */
    public function deleteUser($id){
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(Utilisateurs::class)
            ->find($id);
        if($user) {
            $em->remove($user);
            $em->flush();
            $this->addFlash('success', "L'utilisateur a été supprimé");
        }
        return $this->redirect($this->generateUrl('app_admin'));
    }
}