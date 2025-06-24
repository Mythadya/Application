<?php

namespace App\Service\DateLibrary;

use App\Repository\JourFerieRepository;
use DateTime;
use DateTimeInterface;

class DateService
{
    private JourFerieRepository $jourFerieRepository;

    public function __construct(JourFerieRepository $jourFerieRepository)
    {
        $this->jourFerieRepository = $jourFerieRepository;
    }

    /**
     * Convert any DateTimeInterface to DateTime for modification
     */
    private function ensureDateTime(DateTimeInterface $date): DateTime
    {
        return $date instanceof DateTime ? clone $date : new DateTime($date->format('Y-m-d H:i:s'));
    }

    public function getDaysBetween(DateTimeInterface $date1, DateTimeInterface $date2): int
    {
        return abs($date1->diff($date2)->days) + 1;
    }

    /**
     * Get number of days in a given month and year
     */
  // Add this method to your DateService class
public function getDaysInMonthFromString(string $monthString): int
{
    $date = \DateTime::createFromFormat('Y-m', $monthString);
    if (!$date) {
        throw new \InvalidArgumentException("Invalid month format. Expected 'Y-m'");
    }
    return (int) $date->format('t');
}

    /**
     * Get all months between two dates (format: Y-m)
     */
    public function getMonthsBetweenDates(DateTimeInterface $start, DateTimeInterface $end): array
    {
        $start = $this->ensureDateTime($start);
        $end = $this->ensureDateTime($end);
        
        $months = [];
        $current = (clone $start)->modify('first day of this month');
        
        while ($current <= $end) {
            $months[] = $current->format('Y-m');
            $current->modify('+1 month');
        }
        
        return $months;
    }

    public function getOffsetDaysBetween(DateTimeInterface $start, DateTimeInterface $end): int
    {
        return (int)$start->diff($end)->days;
    }

    /**
     * Get days from project start to target date
     */
    public function getDaysFromStart(DateTimeInterface $startDate, DateTimeInterface $targetDate): int
    {
        return $startDate->diff($targetDate)->days;
    }

    /**
     * Get next working day (skips weekends and holidays)
     */
    public function getNextWorkday(
        DateTimeInterface $date,
        string $zone = 'metropole',
        ?string $year = null
    ): DateTimeInterface {
        $date = $this->ensureDateTime($date);
        $year = $year ?? $date->format('Y');
        $nextDay = clone $date;

        do {
            $nextDay->modify('+1 day');
        } while (
            $this->isWeekend($nextDay) || 
            $this->isHoliday($nextDay, $zone, $year)
        );

        return $nextDay;
    }

    /**
     * Check if date is weekend (Saturday/Sunday)
     */
    private function isWeekend(DateTimeInterface $date): bool
    {
        return in_array($date->format('N'), ['6', '7']);
    }

    /**
     * Check if a date is a holiday
     */
    public function isHoliday(
        DateTimeInterface $date,
        string $zone,
        ?string $year = null
    ): bool {
        $year = $year ?? $date->format('Y');
        
        return $this->jourFerieRepository->createQueryBuilder('j')
            ->where('j.date = :date')
            ->andWhere('j.zone = :zone')
            ->andWhere('j.annee = :year')
            ->setParameter('date', $date->format('Y-m-d'))
            ->setParameter('zone', $zone)
            ->setParameter('year', $year)
            ->getQuery()
            ->getOneOrNullResult() !== null;
    }

    /**
     * Get all weeks between two dates with their metadata
     */

     
    public function getWeeksBetweenDates(DateTimeInterface $startDate, DateTimeInterface $endDate): array
{
    $start = $this->ensureDateTime($startDate);
    $end = $this->ensureDateTime($endDate);
    
    $weeks = [];
    $current = (clone $start)->modify('monday this week');
    $end = (clone $end)->modify('sunday this week');

    while ($current <= $end) {
        $weeks[] = [
            'year' => (int)$current->format('Y'),
            'week' => (int)$current->format('W'),
            'month' => (int)$current->format('m'),
            'number' => (int)$current->format('W'),
            'start_date' => clone $current,
            'end_date' => (clone $current)->modify('+6 days')
        ];
        $current->modify('+1 week');
    }

    return $weeks;
}

    /**
     * Get number of weeks between two dates
     */
    public function getWeeksBetween(DateTimeInterface $date1, DateTimeInterface $date2): int
    {
        $date1 = $this->ensureDateTime($date1)->modify('monday this week');
        $date2 = $this->ensureDateTime($date2)->modify('monday this week');
        
        return (int)($date1->diff($date2)->days / 7);
    }

    /**
     * Get ISO week number for a given date
     */
    public function getWeekNumber(DateTimeInterface $date): int
    {
        return (int)$date->format('W');
    }

    /**
     * Get first day of week (Monday) for a given date
     */
    public function getFirstDayOfWeek(DateTimeInterface $date): DateTimeInterface
    {
        return $this->ensureDateTime($date)->modify('monday this week');
    }

    /**
     * Get first day of month for a given date
     */
    public function getFirstDayOfMonth(DateTimeInterface $date): DateTimeInterface
    {
        return $this->ensureDateTime($date)->modify('first day of this month');
    }
}






















// namespace App\Service\DateLibrary;

// use App\Repository\JourFerieRepository;
// use DateTime;
// use DateTimeInterface;

// class DateService
// {
//     private JourFerieRepository $jourFerieRepository;

//     public function __construct(JourFerieRepository $jourFerieRepository)
//     {
//         $this->jourFerieRepository = $jourFerieRepository;
//     }

// public function getDaysBetween(DateTimeInterface $date1, DateTimeInterface $date2): int
// {
//     return abs($date1->diff($date2)->days) + 1;
// }

// /**
//  * Get number of days in a given month and year
//  */
// public function getDaysInMonth(int $month, int $year): int
// {
//     return cal_days_in_month(CAL_GREGORIAN, $month, $year);
// }


//     /**
//      * Get all months between two dates (format: Y-m)
//      */
//     public function getMonthsBetweenDates(DateTimeInterface $start, DateTimeInterface $end): array
//     {
//         $start = (new DateTime())->setTimestamp($start->getTimestamp());
//         $end = (new DateTime())->setTimestamp($end->getTimestamp());
        
//         $months = [];
//         $current = clone $start;
//         $current->modify('first day of this month');
        
//         while ($current <= $end) {
//             $months[] = $current->format('Y-m');
//             $current->modify('+1 month');
//         }
        
//         return $months;
//     }

// public function getOffsetDaysBetween(DateTimeInterface $start, DateTimeInterface $end): int
// {
//     return (int) $start->diff($end)->days;
// }


//     /**
//      * Get days from project start to target date
//      */
//     public function getDaysFromStart(DateTimeInterface $startDate, DateTimeInterface $targetDate): int
//     {
//         return $startDate->diff($targetDate)->days;
//     }

//     /**
//      * Get next working day (skips weekends and holidays)
//      */
//     public function getNextWorkday(
//         DateTimeInterface $date,
//         string $zone = 'metropole',
//         ?string $year = null
//     ): DateTimeInterface {
//         $date = $this->convertToDateTime($date);
//         $year = $year ?? $date->format('Y');
//         $nextDay = clone $date;

//         do {
//             $nextDay->modify('+1 day');
//         } while (
//             $this->isWeekend($nextDay) || 
//             $this->isHoliday($nextDay, $zone, $year)
//         );

//         return $nextDay;
//     }

    

//     /**
//      * Check if date is weekend (Saturday/Sunday)
//      */
//     private function isWeekend(DateTimeInterface $date): bool
//     {
//         return in_array($date->format('N'), ['6', '7']);
//     }

//     /**
//      * Check if a date is a holiday
//      */
//     public function isHoliday(
//         DateTimeInterface $date,
//         string $zone,
//         ?string $year = null
//     ): bool {
//         $year = $year ?? $date->format('Y');
        
//         return $this->jourFerieRepository->createQueryBuilder('j')
//             ->where('j.date = :date')
//             ->andWhere('j.zone = :zone')
//             ->andWhere('j.annee = :year')
//             ->setParameter('date', $date->format('Y-m-d'))
//             ->setParameter('zone', $zone)
//             ->setParameter('year', $year)
//             ->getQuery()
//             ->getOneOrNullResult() !== null;
//     }

//     /**
//      * Convert DateTimeInterface to DateTime for modification
//      */
//     private function convertToDateTime(DateTimeInterface $date): DateTime
//     {
//         return $date instanceof DateTime ? $date : new DateTime($date->format('Y-m-d'));
//     }
// }