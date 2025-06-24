<?php

namespace App\DataFixtures;

use App\Entity\Utilisateurs;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher) {}

    public function load(ObjectManager $manager): void
    {
        // ROLE_ADMIN user
        $admin = new Utilisateurs();
        $admin->setEmail('admin@example.fr');
        $admin->setPassword($this->hasher->hashPassword($admin, 'admin1234'));
        $admin->setRole('ROLE_ADMIN');
        $admin->setNom('Admin');
        $admin->setPrenom('Admin');
        $admin->setCodepostal('80000');
        $admin->setVille('amiens');
    $admin->setDateInvitation(new \DateTimeImmutable());
        $manager->persist($admin);

        // ROLE_GESTIONNAIRE user
        $gestionnaire = new Utilisateurs();
        $gestionnaire->setEmail('gestionnaire@example.fr');
        $gestionnaire->setPassword($this->hasher->hashPassword($gestionnaire, 'gestionnaire123'));
        $gestionnaire->setRole('ROLE_GESTIONNAIRE');
        $gestionnaire->setNom('Gestionnaire');
        $gestionnaire->setPrenom('Gestionnaire');
        $gestionnaire->setCodepostal('80000');
        $gestionnaire->setVille('amiens');

$gestionnaire->setDateInvitation(new \DateTimeImmutable());
        $manager->persist($gestionnaire);

        // ROLE_CONSULTATION user
        $consultation = new Utilisateurs();
        $consultation->setEmail('consultation@example.fr');
        $consultation->setPassword($this->hasher->hashPassword($consultation, 'consultation123'));
        $consultation->setRole('ROLE_CONSULTATION');
        $consultation->setNom('Consultation');
        $consultation->setPrenom('Consultation');
        $consultation->setCodepostal('80000');
        $consultation->setVille('amiens');
$consultation->setDateInvitation(new \DateTimeImmutable());
        $manager->persist($consultation);

        $manager->flush();
    }
}
