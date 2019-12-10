<?php

namespace App\DataFixtures;

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
        $user = new Utilisateurs();
        $user->setEmail('loicauge@example.com');
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'abcd1234*'));
        $user->setNom("Auge");
        $user->setPrenom("Loic");
        $user->getFkIntervenantId("2");
        $user->setIsAdmin("0");
        $manager->persist($user);

        $manager->flush();
    }
}
