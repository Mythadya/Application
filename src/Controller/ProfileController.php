<?php
// src/Controller/ProfileController.php
namespace App\Controller;

use App\Entity\Utilisateurs;
use App\Form\ProfileEditFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('profile/index.html.twig');
    }

#[Route('/profile/edit', name: 'app_profile_edit')]
public function edit(
    Request $request,
    EntityManagerInterface $em,
    UserPasswordHasherInterface $passwordHasher,
    LoggerInterface $logger = null  // Make logger optional
): Response {
    $user = $this->getUser();
    
    if (!$user instanceof Utilisateurs) {
        throw $this->createAccessDeniedException('Invalid user type');
    }

    $form = $this->createForm(ProfileEditFormType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $newPassword = $form->get('newPassword')->getData();
        $currentPassword = $form->get('currentPassword')->getData();

        if ($newPassword) {
            if (!$passwordHasher->isPasswordValid($user, $currentPassword)) {
                $this->addFlash('error', 'Le mot de passe actuel est incorrect');
                return $this->redirectToRoute('app_profile_edit');
            }

          $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
           $user->setPassword($hashedPassword);

            
            if ($logger) {
                $logger->info('Password changed for user: '.$user->getEmail());
            }
        }

        try {
            $em->flush();
            $this->addFlash('success', 'Profil mis à jour avec succès!');
            
            // Verify the change in database
            $em->refresh($user);
            if ($newPassword && !$passwordHasher->isPasswordValid($user, $newPassword)) {
                $this->addFlash('error', 'Le nouveau mot de passe n\'a pas été enregistré correctement');
            }
            
            return $this->redirectToRoute('app_profile');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Erreur lors de la mise à jour du profil');
            if ($logger) {
                $logger->error('Profile update failed: '.$e->getMessage());
            }
        }
    }

    return $this->render('profile/edit.html.twig', [
        'editForm' => $form->createView()
    ]);
}
}