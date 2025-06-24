<?php

namespace App\Controller;

use App\Entity\PeriodEnEntreprise;
use App\Form\PeriodEnEntrepriseForm;
use App\Repository\PeriodEnEntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/period/en/entreprise')]
final class PeriodEnEntrepriseController extends AbstractController
{
    #[Route('/', name: 'app_period_en_entreprise_index', methods: ['GET'])]
    public function index(PeriodEnEntrepriseRepository $periodEnEntrepriseRepository): Response
    {
        return $this->render('periodEnEntreprise/index.html.twig', [
            'period_en_entreprises' => $periodEnEntrepriseRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_period_en_entreprise_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $periodEnEntreprise = new PeriodEnEntreprise();
        $form = $this->createForm(PeriodEnEntrepriseForm::class, $periodEnEntreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($periodEnEntreprise);
            $entityManager->flush();

            return $this->redirectToRoute('app_period_en_entreprise_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('periodEnEntreprise/new.html.twig', [
            'period_en_entreprise' => $periodEnEntreprise,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_period_en_entreprise_show', methods: ['GET'])]
    public function show(PeriodEnEntreprise $periodEnEntreprise): Response
    {
        return $this->render('periodEnEntreprise/show.html.twig', [
            'period_en_entreprise' => $periodEnEntreprise,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_period_en_entreprise_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PeriodEnEntreprise $periodEnEntreprise, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PeriodEnEntrepriseForm::class, $periodEnEntreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_period_en_entreprise_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('periodEnEntreprise/edit.html.twig', [
            'period_en_entreprise' => $periodEnEntreprise,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_period_en_entreprise_delete', methods: ['POST'])]
    public function delete(Request $request, PeriodEnEntreprise $periodEnEntreprise, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$periodEnEntreprise->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($periodEnEntreprise);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_period_en_entreprise_index', [], Response::HTTP_SEE_OTHER);
    }
}
