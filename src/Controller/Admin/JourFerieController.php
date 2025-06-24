<?php


namespace App\Controller\Admin;

use App\Entity\JourFerie;
use App\Message\SyncHolidaysMessage;
use App\Repository\JourFerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/jour/ferie')]
final class JourFerieController extends AbstractController
{
    #[Route(name: 'app_jour_ferie_index', methods: ['GET'])]
    public function index(JourFerieRepository $jourFerieRepository, Request $request): Response
    {
        $zone = $request->query->get('zone');
        $year = $request->query->get('year');

        $criteria = [];
        if ($zone) $criteria['zone'] = $zone;
        if ($year) $criteria['annee'] = $year;

        return $this->render('admin/jour_ferie/index.html.twig', [
            'jour_feries' => $jourFerieRepository->findBy($criteria, ['date' => 'ASC']),
            'zones' => $jourFerieRepository->findDistinctZones(),
            'annees' => $jourFerieRepository->findDistinctAnnees(),
        ]);
    }

#[Route('/{id}', name: 'app_jour_ferie_show', methods: ['GET'])]
public function show(JourFerie $jourFerie): Response
{
    return $this->render('admin/jour_ferie/show.html.twig', [
        'jour_ferie' => $jourFerie,
    ]);
}


    #[Route('/sync-api', name: 'app_jour_ferie_sync_api', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function syncFromApi(Request $request, MessageBusInterface $messageBus): Response
    {
        $zone = $request->request->get('zone', 'metropole');
        $year = $request->request->get('year', date('Y'));

        $messageBus->dispatch(new SyncHolidaysMessage($zone, $year));
        $this->addFlash('success', 'Synchronisation des jours fériés démarrée');
        return $this->redirectToRoute('app_jour_ferie_index');
    }

    #[Route('/import-csv', name: 'app_jour_ferie_import_csv', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function importCsv(Request $request, JourFerieRepository $repo): Response
    {
        $uploadedFile = $request->files->get('csv_file');
        
        if (!$uploadedFile) {
            $this->addFlash('error', 'Aucun fichier téléchargé');
            return $this->redirectToRoute('app_jour_ferie_index');
        }

        $destination = $this->getParameter('kernel.project_dir').'/var/data/jours_feries';
        $filename = 'uploaded_'.date('YmdHis').'.csv';
        
        try {
            $uploadedFile->move($destination, $filename);
            
            // Execute the import command
            exec(sprintf(
                '%s/bin/console app:import:jours-feries %s',
                $this->getParameter('kernel.project_dir'),
                $destination.'/'.$filename
            ), $output, $returnCode);
            
            if ($returnCode === 0) {
                $this->addFlash('success', 'Import CSV réussi');
            } else {
                $this->addFlash('error', 'Erreur lors de l\'import CSV: '.implode("\n", $output));
            }
        } catch (\Exception $e) {
            $this->addFlash('error', 'Erreur lors de l\'import: '.$e->getMessage());
        }

        return $this->redirectToRoute('app_jour_ferie_index');
    }



    #[Route('/api/list', name: 'app_jour_ferie_api', methods: ['GET'])]
    public function apiList(JourFerieRepository $repo, Request $request): JsonResponse
    {
        $start = $request->query->get('start');
        $end = $request->query->get('end');
        
        $holidays = $start && $end 
            ? $repo->findBetweenDates(new \DateTime($start), new \DateTime($end))
            : $repo->findAll();

        return $this->json(array_map(fn(JourFerie $j) => [
            'id' => $j->getId(),
            'title' => $j->getNom(),
            'start' => $j->getDate()->format('Y-m-d'),
            'allDay' => true,
            'className' => 'holiday-event',
            'extendedProps' => [
                'zone' => $j->getZone()
            ]
        ], $holidays));
    }
}