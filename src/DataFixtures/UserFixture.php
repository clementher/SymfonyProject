<?php

namespace App\DataFixtures;

use App\Entity\Intervenant;
use App\Entity\Matiere;
use App\Entity\Utilisateurs;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $email = "admin@admin.fr";
        $roles[] = 'ROLE_ADMIN';
        $admin = new Utilisateurs();
        $admin->setNom("Admin");
        $admin->setPrenom("Admin");
        $admin->setEmail($email);
        $admin->setRoles($roles);
        $admin->setPassword($this->passwordEncoder->encodePassword($admin, "admin*"));
        $admin->setIsAdmin(1);
        $manager->persist($admin);

        $inte = new Intervenant();
        $inte->setPrenom("ENTREPRISE");
        $inte->setNom("");
        $inte->setSpecialiteprofessionnelle("ENTREPRISE");
        $inte->setEmail("entreprise@example.fr");
        $manager->persist($inte);

        $manager->flush();

        $mat = new Matiere();
        $mat->setFkIntervenant($inte);
        $mat->setIntitule("ENTREPRISE");
        $mat->setIsSpecialite(0);
        $mat->setDuree(0);

        $manager->persist($mat);

        $manager->flush();
    }
}
