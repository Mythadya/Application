<?php


// src/SyncHolidaysMessage.php
namespace App\Message;

class SyncHolidaysMessage
{
    private string $zone;
    private string $year;

    public function __construct(string $zone, string $year)
    {
        $this->zone = $zone;
        $this->year = $year;
    }

    public function getZone(): string
    {
        return $this->zone;
    }

    public function getYear(): string
    {
        return $this->year;
    }
}