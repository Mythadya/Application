<?php

namespace App\Controller;

use App\Entity\Interruption;
use App\Form\InterruptionForm;
use App\Repository\InterruptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/interruption')]
final class InterruptionController extends AbstractController
{
    #[Route(name: 'app_interruption_index', methods: ['GET'])]
    public function index(InterruptionRepository $interruptionRepository): Response
    {
        return $this->render('interruption/index.html.twig', [
            'interruptions' => $interruptionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_interruption_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $interruption = new Interruption();
        $form = $this->createForm(InterruptionForm::class, $interruption);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($interruption);
            $entityManager->flush();

            return $this->redirectToRoute('app_interruption_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('interruption/new.html.twig', [
            'interruption' => $interruption,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_interruption_show', methods: ['GET'])]
    public function show(Interruption $interruption): Response
    {
        return $this->render('interruption/show.html.twig', [
            'interruption' => $interruption,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_interruption_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Interruption $interruption, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(InterruptionForm::class, $interruption);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_interruption_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('interruption/edit.html.twig', [
            'interruption' => $interruption,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_interruption_delete', methods: ['POST'])]
    public function delete(Request $request, Interruption $interruption, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$interruption->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($interruption);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_interruption_index', [], Response::HTTP_SEE_OTHER);
    }
}
