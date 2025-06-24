<?php

namespace App\Controller;

use App\Entity\Utilisateurs;
use App\Entity\Invitation;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface; // <-- Add this import
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register/invite/{token}', name: 'app_register_by_invite')]
    public function registerByInvite(
        string $token,
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher,
        LoggerInterface $logger
    ): Response {
        $logger->debug('Checking invitation', ['token' => $token]);
        
        $invitation = $em->getRepository(Invitation::class)->findOneBy(['token' => $token]);
        
        if (!$invitation) {
            $logger->error('Invitation not found', ['token' => $token]);
            throw $this->createNotFoundException('Lien invalide ou expiré.');
        }
        
        if ($invitation->isUsed()) {
            $logger->error('Invitation already used', ['token' => $token]);
            throw $this->createNotFoundException('Lien invalide ou expiré.');
        }
        
        if ($invitation->getExpiresAt() < new \DateTimeImmutable()) {
            $logger->error('Invitation expired', [
                'token' => $token,
                'expiresAt' => $invitation->getExpiresAt()->format('Y-m-d H:i:s'),
                'now' => (new \DateTime())->format('Y-m-d H:i:s')
            ]);
            throw $this->createNotFoundException('Lien invalide ou expiré.');
        }


        $utilisateur = new Utilisateurs();
        $utilisateur->setEmail($invitation->getEmail());
        // $utilisateur->setRole('$invitation->getRole'); 
        $utilisateur->setRole($invitation->getRole());  //changed to 100625
        $utilisateur->setDateInvitation(new \DateTimeImmutable()); // Register date

        $form = $this->createForm(RegistrationFormType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateur->setPassword(
                $passwordHasher->hashPassword($utilisateur, $form->get('plainPassword')->getData())
            );

            $em->persist($utilisateur);
            $invitation->setUsed(true);
            $em->flush();

            $this->addFlash('success', 'Compte créé avec succès ! Vous pouvez maintenant vous connecter.');

            return $this->redirectToRoute('app_connexion');
        }

        return $this->render('registration/invite.html.twig', [
            'registrationForm' => $form,
            'email' => $invitation->getEmail()
        ]);
    }
}


