<?php

namespace App\DataFixtures;

use App\Entity\Formateur;
use App\Entity\Formation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class FormationFormateurFixtures extends Fixture
{
public function load(ObjectManager $manager): void
{

return;
}}
//i lefed the class and put the fixture in comments because i dont to use it
    // Create formateurs
//     $formateurs = [];
//     $formateurData = [
//         ['nom' => 'François', 'prenom' => 'Regis', 'email' => 'regis@example.com'],
//     ];

//     foreach ($formateurData as $data) {
//         $formateur = new Formateur();
//         $formateur->setNom($data['nom']);
//         $formateur->setPrenom($data['prenom']);
//         $formateur->setEmail($data['email']);
//         $manager->persist($formateur);
//         $formateurs[$data['nom'] . ' ' . $data['prenom']] = $formateur;
//     }

//     // Create formations with PROPER structure
//     $formations = [
//         [
//             'name' => 'CDA', // ← String value here
//             'formateur' => $formateurs['François Regis'] // ← Formateur object here
//         ],
//         // Add more formations as needed
//     ];


//         foreach ($formations as $data) {
//     $formation = new Formation();
//     $formation->setNom($data['name']);
//     $formation->setActifFormation(true);
//     $formation->setNumero('001');
//     $formation->setEtat('Active');
//     $formation->setTitreProfessionnel('Titre Pro Example');
//     $formation->setNiveau(1);
//     $formation->setNbStagiairesPrevisionnel(10);
//     $formation->setGroupeRattachement('Groupe A');
//     $formation->setDateDebut(new \DateTime('2024-03-04'));
//     $formation->setDateFin(new \DateTime('2025-08-22'));
//     $formation->setNombreHeures(750);

//     $formation->addFormateur($data['formateur']);

//     $manager->persist($formation);
// }
// $manager->flush();
// }
// }



















//     foreach ($formations as $data) {
//         $formation = new Formation();
//         $formation->setNom($data['name']); // ← Pass string to setNom()
        
//         // Set other properties
//         $formation->setActifFormation(true);
//         $formation->setNumero('001');
//         $formation->setEtat('Active');
//         $formation->setTitreProfessionnel('Titre Pro Example');
//         $formation->setNiveau(1);
//         $formation->setNbStagiairesPrevisionnel(10);
//         $formation->setGroupeRattachement('Groupe A');
//         $formation->setDateDebut(new \DateTime('2025-01-01'));
//         $formation->setDateFin(new \DateTime('2025-06-30'));

//         // Add formateur using addFormateur()
//         $formation->addFormateur($data['formateur']);
        
//         $manager->persist($formation);
//     }

//     $manager->flush();
// }
// }