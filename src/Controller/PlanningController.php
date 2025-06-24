<?php


namespace App\Controller;

use App\Repository\FormationRepository;
use App\Repository\JourFerieRepository;
use App\Service\DateLibrary\DateService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlanningController extends AbstractController
{
    #[Route('/', name: 'app_planning')]
   public function index(
    FormationRepository $formationRepository,
    JourFerieRepository $jourFerieRepository,
    DateService $dateService
): Response {
    $formations = $formationRepository->findAllWithRelations();
    $today = new \DateTime();

    // Calculate date range
    $startDate = new \DateTime('2024-01-01');
    $endDate = new \DateTime('2030-12-31');

    foreach ($formations as $f) {
        if ($f->getDateDebut() < $startDate) {
            $startDate = $f->getDateDebut();
        }
        if ($f->getDateFin() > $endDate) {
            $endDate = $f->getDateFin();
        }
    }

    // Add margin
    $startDate->modify('-1 year');
    $endDate->modify('+1 year');

    // Get all necessary data
    $months = $dateService->getMonthsBetweenDates($startDate, $endDate);
    $allWeeks = $dateService->getWeeksBetweenDates($startDate, $endDate);
    $holidays = $jourFerieRepository->findBetweenDates($startDate, $endDate);
    
    // Calculate weeks per year
    $yearlyWeeks = [];
    foreach ($allWeeks as $week) {
        $year = $week['year'];
        $yearlyWeeks[$year] = ($yearlyWeeks[$year] ?? 0) + 1;
    }

    // Calculate stagiaires per week
    foreach ($allWeeks as &$week) {
        $weekStart = $week['start_date'];
        $weekEnd = $week['end_date'];
        
        $totalStagiaires = 0;
        $activeStagiaires = 0;
        
        foreach ($formations as $formation) {
           if ($formation->getDateDebut() <= $weekEnd && $formation->getDateFin() >= $weekStart) {
    $totalStagiaires += ($formation->getnombreStagiaires() ?? 0); // Fixed line
    $activeStagiaires++;
}

        }
        
        $week['total_stagiaires'] = $totalStagiaires;
        $week['active_stagiaires'] = $activeStagiaires;
    }

    // Group formations
    $groupedFormations = [];
    foreach ($formations as $f) {
        $group = $f->getGroupeRattachement() ?? 'Non groupé';
        $groupedFormations[$group][] = $f;
    }

    // Calculate day width
$totalDays = $dateService->getDaysBetween($startDate, $endDate);
$dayWidth = min(6, max(5, 1200 / $totalDays));

return $this->render('planning/index.html.twig', [
    'grouped_formations' => $groupedFormations,
    'holidays' => $holidays,
    'months' => $months,
    'yearly_weeks' => $yearlyWeeks,
    'all_weeks' => $allWeeks,
    'start_date' => $startDate,
    'end_date' => $endDate,
    'day_width' => $dayWidth,
    'total_days' => $totalDays,
    'date_service' => $dateService
]);

}
}



















// this is working last controller
// namespace App\Controller;

// use App\Repository\FormationRepository;
// use App\Repository\JourFerieRepository;
// use App\Service\DateLibrary\DateService;
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Annotation\Route;

// class PlanningController extends AbstractController
// {
// #[Route('/', name: 'app_planning')]
// public function index(
// FormationRepository $formationRepository,
// JourFerieRepository $jourFerieRepository,
// DateService $dateService
// ): Response {
// $formations = $formationRepository->findAllWithRelations();
// $today = new \DateTime();

// // Calculate date range
// $startDate = new \DateTime('2024-01-01');
// $endDate = new \DateTime('2030-12-31');

// foreach ($formations as $f) {
// $startDate = min($startDate, $f->getDateDebut());
// $endDate = max($endDate, $f->getDateFin());
// }

// // Months between dates
// $months = $dateService->getMonthsBetweenDates($startDate, $endDate);

// // Calculate months & weeks per year
// $weekNumbersPerMonth = [];
// $monthsInYear = [];

// foreach ($months as $month) {
// list($year, $monthNum) = explode('-', $month);
// $daysInMonth = date('t', strtotime($month . '-01'));

// $firstDay = new \DateTime("$month-01");
// $lastDay = new \DateTime("$month-$daysInMonth");

// $weekNumbers = [];
// for ($day = clone $firstDay; $day <= $lastDay; $day->modify('+1 day')) {
// $week = $day->format('W');
// $weekNumbers[$week] = true;
// }

// $weekNumbersPerMonth[$month] = array_keys($weekNumbers);
// $monthsInYear[$year] = ($monthsInYear[$year] ?? 0) + 1;
// }

// // Yearly weeks
// $yearlyWeeks = [];
// $current = clone $startDate;
// while ($current <= $endDate) {
// $year = (int) $current->format('Y');
// $week = (int) $current->format('W');
// $yearlyWeeks[$year] = ($yearlyWeeks[$year] ?? 0) + 1;
// $current->modify('+1 week');
// }

// // Build all_weeks array (week by week)
// $allWeeks = [];
// $currentWeek = clone $startDate;
// while ($currentWeek <= $endDate) {
// $weekNumber = (int) $currentWeek->format('W');
// $isoYear = (int) $currentWeek->format('o');
// $displayYear = (int) $currentWeek->format('Y');

// $allWeeks[] = [
// 'number' => $weekNumber,
// 'iso_year' => $isoYear,
// 'display_year' => $displayYear,
// 'total_stagiaires' => null, // Replace with real data if needed
// 'active_stagiaires' => null // Replace with real data if needed
// ];

// $currentWeek->modify('+1 week');
// }

// // Group formations
// $groupedFormations = [];
// foreach ($formations as $f) {
// $group = $f->getGroupeRattachement() ?? 'Non groupé';
// $groupedFormations[$group][] = $f;
// }

// // Get holidays
// $holidays = $jourFerieRepository->findBetweenDates($startDate, $endDate);

// // Marker for today
// $currentDayOffset = $dateService->getOffsetDaysBetween($startDate, $today);

// return $this->render('planning/index.html.twig', [
// 'grouped_formations' => $groupedFormations,
// 'holidays' => $holidays,
// 'months' => $months,
// 'week_numbers_per_month' => $weekNumbersPerMonth,
// 'months_in_year' => $monthsInYear,
// 'yearly_weeks' => $yearlyWeeks,
// 'all_weeks' => $allWeeks, // ✅ FIXED
// 'start_date' => $startDate,
// 'end_date' => $endDate,
// 'dayWidth' => 2,
// 'current_day_position' => $currentDayOffset * 2,
// 'date_service' => $dateService
// ]);
// }
// }


// namespace App\Controller;

// use App\Repository\FormationRepository;
// use App\Repository\JourFerieRepository;
// use App\Service\DateLibrary\DateService;
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Annotation\Route;

// class PlanningController extends AbstractController
// {
//     #[Route('/', name: 'app_planning')]
//     public function index(
//         FormationRepository $formationRepository,
//         JourFerieRepository $jourFerieRepository,
//         DateService $dateService
//     ): Response {
//         $formations = $formationRepository->findAllWithRelations();
//         $today = new \DateTime();

//         // Calculate date range
//         $startDate = new \DateTime('2024-01-01');
//         $endDate = new \DateTime('2030-12-31');

//         foreach ($formations as $f) {
//             $startDate = min($startDate, $f->getDateDebut());
//             $endDate = max($endDate, $f->getDateFin());
//         }

//         // Get all weeks in the range
//         $weeks = $dateService->getWeeksBetweenDates($startDate, $endDate);
        
//         // Group weeks by year for the years header
//         $yearsWeeks = [];
//         foreach ($weeks as $week) {
//             $year = $week['year'];
//             $yearsWeeks[$year] = ($yearsWeeks[$year] ?? 0) + 1;
//         }

//         // Group weeks by month for the months header
//         $months = [];
//         $currentMonth = null;
//         $monthCount = 0;
        
//         foreach ($weeks as $week) {
//             $monthKey = $week['year'] . '-' . str_pad($week['month'], 2, '0', STR_PAD_LEFT);
            
//             if ($monthKey !== $currentMonth) {
//                 if ($currentMonth !== null) {
//                     $months[] = [
//                         'name' => $this->getMonthName($currentMonth),
//                         'weeks' => $monthCount,
//                         'year' => explode('-', $currentMonth)[0],
//                         'month' => explode('-', $currentMonth)[1]
//                     ];
//                 }
//                 $currentMonth = $monthKey;
//                 $monthCount = 0;
//             }
//             $monthCount++;
//         }
        
//         // Add the last month
//         if ($currentMonth !== null) {
//             $months[] = [
//                 'name' => $this->getMonthName($currentMonth),
//                 'weeks' => $monthCount,
//                 'year' => explode('-', $currentMonth)[0],
//                 'month' => explode('-', $currentMonth)[1]
//             ];
//         }

//         // Group formations
//         $groupedFormations = [];
//         foreach ($formations as $f) {
//             $group = $f->getGroupeRattachement() ?? 'Non groupé';
//             $groupedFormations[$group][] = $f;
//         }

//         // Get holidays with their full data
//         $holidays = $jourFerieRepository->findBetweenDates($startDate, $endDate);
//         $currentWeekPosition = $dateService->getWeeksBetween($startDate, $today) * 30;

//         return $this->render('planning/index.html.twig', [
//             'grouped_formations' => $groupedFormations,
//             'holidays' => $holidays,
//             'years_weeks' => $yearsWeeks,
//             'months' => $months,
//             'weeks' => $weeks,
//             'start_date' => $startDate,
//             'end_date' => $endDate,
//             'current_week_position' => $currentWeekPosition,
//             'date_service' => $dateService
//         ]);
//     }

//     private function getMonthName(string $monthKey): string
//     {
//         $months = [
//             '01' => 'Janvier', '02' => 'Février', '03' => 'Mars',
//             '04' => 'Avril', '05' => 'Mai', '06' => 'Juin',
//             '07' => 'Juillet', '08' => 'Août', '09' => 'Septembre',
//             '10' => 'Octobre', '11' => 'Novembre', '12' => 'Décembre'
//         ];
        
//         $monthNum = explode('-', $monthKey)[1];
//         return $months[$monthNum] ?? $monthKey;
//     }
// }







// namespace App\Controller;

// use App\Repository\FormationRepository;
// use App\Repository\JourFerieRepository;
// use App\Service\DateLibrary\DateService;
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Annotation\Route;

// class PlanningController extends AbstractController
// {
//     #[Route('/', name: 'app_planning')]
//     public function index(
//         FormationRepository $formationRepository,
//         JourFerieRepository $jourFerieRepository,
//         DateService $dateService
//     ): Response {
//         $formations = $formationRepository->findAllWithRelations();
//         $today = new \DateTime();

//         // Determine min/max date based on formations
//         $startDate = new \DateTime('2030-12-31');
//         $endDate = new \DateTime('2024-01-01');

//         foreach ($formations as $f) {
//             if ($f->getDateDebut() < $startDate) {
//                 $startDate = $f->getDateDebut();
//             }
//             if ($f->getDateFin() > $endDate) {
//                 $endDate = $f->getDateFin();
//             }
//         }

//         // Optional margin
//         $startDate->modify('-1 year');
//         $endDate->modify('+1 year');

//         //  Months for headers
//         $months = $dateService->getMonthsBetweenDates($startDate, $endDate);
//         $weekNumbersPerMonth = [];
//         $monthsInYear = [];

//         foreach ($months as $month) {
//             list($year, $monthNum) = explode('-', $month);
//             $daysInMonth = date('t', strtotime($month . '-01'));

//             $firstDay = new \DateTime("$month-01");
//             $lastDay = new \DateTime("$month-$daysInMonth");

//             $weekNumbers = [];
//             for ($day = clone $firstDay; $day <= $lastDay; $day->modify('+1 day')) {
//                 $week = $day->format('W');
//                 $weekNumbers[$week] = true;
//             }

//             $weekNumbersPerMonth[$month] = array_keys($weekNumbers);
//             $monthsInYear[$year] = ($monthsInYear[$year] ?? 0) + 1;
//         }

//         //  Weeks per year
//         $yearlyWeeks = [];
//         $current = clone $startDate;
//         while ($current <= $endDate) {
//             $year = (int)$current->format('Y');
//             $yearlyWeeks[$year] = ($yearlyWeeks[$year] ?? 0) + 1;
//             $current->modify('+1 week');
//         }

//         //  Build all_weeks and calculate total stagiaires
//         $allWeeks = [];
//         $currentWeek = clone $startDate;

//         while ($currentWeek <= $endDate) {
//             $weekNumber = (int)$currentWeek->format('W');
//             $isoYear = (int)$currentWeek->format('o');
//             $displayYear = (int)$currentWeek->format('Y');

//             $weekStart = clone $currentWeek;
//             $weekEnd = (clone $currentWeek)->modify('+6 days');

//             $totalStagiaires = 0;
//             $activeStagiaires = 0;

//             foreach ($formations as $formation) {
//                 if (
//                     $formation->getDateDebut() <= $weekEnd &&
//                     $formation->getDateFin() >= $weekStart
//                 ) {
//                     $totalStagiaires += $formation->getNbStagiairesPrevisionnel() ?? 0;
//                     $activeStagiaires++;
//                 }
//             }

//             $allWeeks[] = [
//                 'number' => $weekNumber,
//                 'iso_year' => $isoYear,
//                 'display_year' => $displayYear,
//                 'total_stagiaires' => $totalStagiaires,
//                 'active_stagiaires' => $activeStagiaires
//             ];

//             $currentWeek->modify('+1 week');
//         }

//         //  Set active weeks per formation
//         foreach ($formations as $formation) {
//             $activeWeeks = 0;
//             $formationStart = $formation->getDateDebut();
//             $formationEnd = $formation->getDateFin();

//             foreach ($allWeeks as $week) {
//                 $weekStart = new \DateTime();
//                 $weekStart->setISODate($week['iso_year'], $week['number']);
//                 $weekEnd = (clone $weekStart)->modify('+6 days');

//                 if ($formationStart <= $weekEnd && $formationEnd >= $weekStart) {
//                     $activeWeeks++;
//                 }
//             }

//             $formation->setActiveStagiaires($activeWeeks); // NOT persisted to DB unless you call flush()
//         }

//         //  Group formations
//         $groupedFormations = [];
//         foreach ($formations as $f) {
//             $group = $f->getGroupeRattachement() ?? 'Non groupé';
//             $groupedFormations[$group][] = $f;
//         }

//         //  Holidays
//         $holidays = $jourFerieRepository->findBetweenDates($startDate, $endDate);

//         //  Today's marker
//         $currentDayOffset = $dateService->getOffsetDaysBetween($startDate, $today);

//         // Render the Gantt chart
//         return $this->render('planning/index.html.twig', [
//             'grouped_formations' => $groupedFormations,
//             'holidays' => $holidays,
//             'months' => $months,
//             'week_numbers_per_month' => $weekNumbersPerMonth,
//             'months_in_year' => $monthsInYear,
//             'yearly_weeks' => $yearlyWeeks,
//             'all_weeks' => $allWeeks,
//             'start_date' => $startDate,
//             'end_date' => $endDate,
//             'dayWidth' => 2,
//             'current_day_position' => $currentDayOffset * 2,
//             'date_service' => $dateService
//         ]);
//     }
// }
