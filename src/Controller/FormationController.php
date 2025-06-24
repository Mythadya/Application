<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationForm;
use App\Repository\FormationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/formation')]
final class FormationController extends AbstractController
{
    #[Route(name: 'app_formation_index', methods: ['GET'])]
    public function index(FormationRepository $formationRepository): Response
    {
        return $this->render('admin/formation/index.html.twig', [
            'formations' => $formationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_formation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $formation = new Formation();
        $form = $this->createForm(FormationForm::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($formation);
            $entityManager->flush();

            return $this->redirectToRoute('app_formation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/formation/new.html.twig', [
            'formation' => $formation,
            'form' => $form,
        ]);
    }
 #[Route('/{id}', name: 'app_formation_show', methods: ['GET'], requirements: ['id' => '\d+'])]
  public function show(int $id, FormationRepository $repository): Response
{
    $formation = $repository->find($id);
    if (!$formation) {
        throw $this->createNotFoundException('Formation non trouvée.');
    }

    return $this->render('admin/formation/show.html.twig', [
        'formation' => $formation,
    ]);
}


  #[Route('/{id}/edit', name: 'app_formation_edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
public function edit(Request $request, int $id, FormationRepository $repository, EntityManagerInterface $em): Response
{
    $formation = $repository->find($id);
    if (!$formation) {
        throw $this->createNotFoundException('Formation non trouvée.');
    }

    $form = $this->createForm(FormationForm::class, $formation);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->flush();
        return $this->redirectToRoute('app_formation_index');
    }

    return $this->render('admin/formation/edit.html.twig', [
        'formation' => $formation,
        'form' => $form,
    ]);
}


   #[Route('/{id}', name: 'app_formation_delete', methods: ['POST'], requirements: ['id' => '\d+'])]
public function delete(Request $request, int $id, FormationRepository $repository, EntityManagerInterface $em): Response
{
    $formation = $repository->find($id);
    if (!$formation) {
        throw $this->createNotFoundException('Formation non trouvée.');
    }

    if ($this->isCsrfTokenValid('delete' . $formation->getId(), $request->getPayload()->getString('_token'))) {
        $em->remove($formation);
        $em->flush();
    }

    return $this->redirectToRoute('app_formation_index');
}

}
