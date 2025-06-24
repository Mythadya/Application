<?php

namespace App\Controller;

use App\Entity\Invitation;
use App\Form\InvitationForm;
use App\Repository\InvitationRepository;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Psr\Log\LoggerInterface; // Import the LoggerInterface

#[Route('/invitation')]
final class InvitationController extends AbstractController
{
    private $logger; // Add the logger property

    public function __construct(LoggerInterface $logger) // Inject the logger in the constructor
    {
        $this->logger = $logger; // Initialize the logger
    }

    #[Route(name: 'app_invitation_index', methods: ['GET'])]
    public function index(InvitationRepository $invitationRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('invitation/index.html.twig', [
            'invitations' => $invitationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_invitation_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        SendMailService $sendMailService
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $invitation = new Invitation();
        $form = $this->createForm(InvitationForm::class, $invitation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $token = bin2hex(random_bytes(32));
            $invitation
                ->setToken($token)
                ->setExpiresAt(new \DateTime('+7 days'))
                ->setUsed(false);

            $entityManager->persist($invitation);
            $entityManager->flush();

            // Send email
            try {
                $sendMailService->send(
                    'no-reply@training-center.com',
                    $invitation->getEmail(),
                    'Invitation à rejoindre la plateforme',
                    'invitation',
                    [
                        'registrationUrl' => $this->generateUrl(
                            'app_register_by_invite',
                            ['token' => $token],
                            UrlGeneratorInterface::ABSOLUTE_URL
                        ),
                        'role' => $invitation->getRole()
                    ]
                );

                $this->addFlash('success', 'Invitation envoyée avec succès !');

            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur lors de l\'envoi de l\'invitation : ' . $e->getMessage());
                $this->logger->error('Email sending error: ' . $e->getMessage(), ['exception' => $e]);
            }

            return $this->redirectToRoute('app_invitation_index');
        }

        return $this->render('invitation/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_invitation_show', methods: ['GET'])]
    public function show(Invitation $invitation): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('invitation/show.html.twig', [
            'invitation' => $invitation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_invitation_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Invitation $invitation,
        EntityManagerInterface $entityManager
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(InvitationForm::class, $invitation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Invitation mise à jour !');
            return $this->redirectToRoute('app_invitation_index');
        }

        return $this->render('invitation/edit.html.twig', [
            'invitation' => $invitation,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_invitation_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Invitation $invitation,
        EntityManagerInterface $entityManager
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($this->isCsrfTokenValid('delete'.$invitation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($invitation);
            $entityManager->flush();
            $this->addFlash('success', 'Invitation supprimée !');
        }

        return $this->redirectToRoute('app_invitation_index');
    }
}



















// namespace App\Controller;

// use App\Entity\Invitation;
// use App\Form\InvitationForm;
// use App\Repository\InvitationRepository;
// use App\Service\SendMailService;
// use Doctrine\ORM\EntityManagerInterface;
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Attribute\Route;
// use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

// #[Route('/invitation')]
// final class InvitationController extends AbstractController
// {
//     #[Route(name: 'app_invitation_index', methods: ['GET'])]
//     public function index(InvitationRepository $invitationRepository): Response
//     {
//         $this->denyAccessUnlessGranted('ROLE_ADMIN');
//         return $this->render('invitation/index.html.twig', [
//             'invitations' => $invitationRepository->findAll(),
//         ]);
//     }

// #[Route('/new', name: 'app_invitation_new', methods: ['GET', 'POST'])]
// public function new(
//     Request $request, 
//     EntityManagerInterface $entityManager,
//     SendMailService $sendMailService
// ): Response {
//     $this->denyAccessUnlessGranted('ROLE_ADMIN');
    
//     $invitation = new Invitation();
//     $form = $this->createForm(InvitationForm::class, $invitation);
//     $form->handleRequest($request);

//     if ($form->isSubmitted() && $form->isValid()) {
//         $token = bin2hex(random_bytes(32));
//         $invitation
//             ->setToken($token)
//             ->setExpiresAt(new \DateTime('+7 days'))
//             ->setUsed(false);

//         $entityManager->persist($invitation);
//         $entityManager->flush();

//         // Send email
//         $sendMailService->send(
//             'no-reply@training-center.com',
//             $invitation->getEmail(),
//             'Invitation à rejoindre la plateforme',
//             'invitation',
//             // 'emails/invitation.html.twig',  // Correct path
//             [
//                 'registrationUrl' => $this->generateUrl(
//                     'app_register_by_invite',
//                     ['token' => $token],
//                     UrlGeneratorInterface::ABSOLUTE_URL
//                 ),
//                 'role' => $invitation->getRole()
//             ]
//         );

//         $this->addFlash('success', 'Invitation envoyée avec succès !');
//         return $this->redirectToRoute('app_invitation_index');
//     }

//     return $this->render('invitation/new.html.twig', [
//         'form' => $form->createView(),
//     ]);
// }

//     #[Route('/{id}', name: 'app_invitation_show', methods: ['GET'])]
//     public function show(Invitation $invitation): Response
//     {
//         $this->denyAccessUnlessGranted('ROLE_ADMIN');
//         return $this->render('invitation/show.html.twig', [
//             'invitation' => $invitation,
//         ]);
//     }

//     #[Route('/{id}/edit', name: 'app_invitation_edit', methods: ['GET', 'POST'])]
//     public function edit(
//         Request $request, 
//         Invitation $invitation, 
//         EntityManagerInterface $entityManager
//     ): Response {
//         $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
//         $form = $this->createForm(InvitationForm::class, $invitation);
//         $form->handleRequest($request);

//         if ($form->isSubmitted() && $form->isValid()) {
//             $entityManager->flush();
//             $this->addFlash('success', 'Invitation mise à jour !');
//             return $this->redirectToRoute('app_invitation_index');
//         }

//         return $this->render('invitation/edit.html.twig', [
//             'invitation' => $invitation,
//             'form' => $form->createView(),
//         ]);
//     }

//     #[Route('/{id}', name: 'app_invitation_delete', methods: ['POST'])]
//     public function delete(
//         Request $request, 
//         Invitation $invitation, 
//         EntityManagerInterface $entityManager
//     ): Response {
//         $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
//         if ($this->isCsrfTokenValid('delete'.$invitation->getId(), $request->request->get('_token'))) {
//             $entityManager->remove($invitation);
//             $entityManager->flush();
//             $this->addFlash('success', 'Invitation supprimée !');
//         }

//         return $this->redirectToRoute('app_invitation_index');
//     }
// }



























// namespace App\Controller;

// use App\Entity\Invitation;
// use App\Form\InvitationForm;
// use App\Repository\InvitationRepository;
// use Doctrine\ORM\EntityManagerInterface;
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Attribute\Route;

// #[Route('/invitation')]
// final class InvitationController extends AbstractController
// {
//     #[Route(name: 'app_invitation_index', methods: ['GET'])]
//     public function index(InvitationRepository $invitationRepository): Response
//     {
//         return $this->render('invitation/index.html.twig', [
//             'invitations' => $invitationRepository->findAll(),
//         ]);
//     }

//     #[Route('/new', name: 'app_invitation_new', methods: ['GET', 'POST'])]
//     public function new(Request $request, EntityManagerInterface $entityManager): Response
//     {
//         $invitation = new Invitation();
//         $form = $this->createForm(InvitationForm::class, $invitation);
//         $form->handleRequest($request);

//         if ($form->isSubmitted() && $form->isValid()) {
//             $entityManager->persist($invitation);
//             $entityManager->flush();

//             return $this->redirectToRoute('app_invitation_index', [], Response::HTTP_SEE_OTHER);
//         }

//         return $this->render('invitation/new.html.twig', [
//             'invitation' => $invitation,
//             'form' => $form,
//         ]);
//     }

//     #[Route('/{id}', name: 'app_invitation_show', methods: ['GET'])]
//     public function show(Invitation $invitation): Response
//     {
//         return $this->render('invitation/show.html.twig', [
//             'invitation' => $invitation,
//         ]);
//     }

//     #[Route('/{id}/edit', name: 'app_invitation_edit', methods: ['GET', 'POST'])]
//     public function edit(Request $request, Invitation $invitation, EntityManagerInterface $entityManager): Response
//     {
//         $form = $this->createForm(InvitationForm::class, $invitation);
//         $form->handleRequest($request);

//         if ($form->isSubmitted() && $form->isValid()) {
//             $entityManager->flush();

//             return $this->redirectToRoute('app_invitation_index', [], Response::HTTP_SEE_OTHER);
//         }

//         return $this->render('invitation/edit.html.twig', [
//             'invitation' => $invitation,
//             'form' => $form,
//         ]);
//     }

//     #[Route('/{id}', name: 'app_invitation_delete', methods: ['POST'])]
//     public function delete(Request $request, Invitation $invitation, EntityManagerInterface $entityManager): Response
//     {
//         if ($this->isCsrfTokenValid('delete'.$invitation->getId(), $request->getPayload()->getString('_token'))) {
//             $entityManager->remove($invitation);
//             $entityManager->flush();
//         }

//         return $this->redirectToRoute('app_invitation_index', [], Response::HTTP_SEE_OTHER);
//     }
// }
