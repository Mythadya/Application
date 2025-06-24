<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class DateExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('month_name_fr', [$this, 'getFrenchMonthName']),
        ];
    }

    public function getFrenchMonthName(string $monthNumber): string
    {
        $months = [
            '01' => 'Janvier', '02' => 'Février', '03' => 'Mars',
            '04' => 'Avril', '05' => 'Mai', '06' => 'Juin',
            '07' => 'Juillet', '08' => 'Août', '09' => 'Septembre',
            '10' => 'Octobre', '11' => 'Novembre', '12' => 'Décembre'
        ];
        
        return $months[$monthNumber] ?? $monthNumber;
    }
}