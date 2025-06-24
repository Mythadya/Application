<?php
// src/Controller/HolidayController.php
namespace App\Controller;

use App\Repository\JourFerieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HolidayController extends AbstractController
{
// In HolidayController
#[Route('/jours-feries', name: 'app_public_holidays')]
public function index(JourFerieRepository $jourFerieRepository): Response
{
    $currentYear = date('Y');
    $holidays = $jourFerieRepository->findBy(
        ['annee' => $currentYear],
        ['date' => 'ASC']
    );

    return $this->render('holiday/index.html.twig', [
        'holidays' => $holidays,
    ]);
}
}