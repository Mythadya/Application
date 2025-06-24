<?php

// src/Service/JourFerieApiService.php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use App\Entity\JourFerie;

class JourFerieApiService
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private EntityManagerInterface $entityManager,
        private ?LoggerInterface $logger = null
    ) {}

    public function syncHolidaysForZone(string $zone, string $year, bool $force = false): int
    {
        try {
            $apiUrl = "https://calendrier.api.gouv.fr/jours-feries/{$zone}/{$year}.json";
            $response = $this->httpClient->request('GET', $apiUrl);
            
            $holidays = $response->toArray();
            $importedCount = 0;

            foreach ($holidays as $date => $name) {
                $holidayDate = new \DateTime($date);
                $existing = $this->entityManager->getRepository(JourFerie::class)
                    ->findOneBy(['date' => $holidayDate, 'zone' => $zone]);

                if ($force || !$existing) {
                    if ($existing) {
                        $existing->setNom($name);
                    } else {
                        $holiday = new JourFerie();
                        $holiday->setDate($holidayDate)
                            ->setNom($name)
                            ->setZone($zone)
                            ->setAnnee($year);
                        $this->entityManager->persist($holiday);
                    }
                    $importedCount++;
                }
            }

            $this->entityManager->flush();
            return $importedCount;

        } catch (\Exception $e) {
            $this->logger?->error("Holiday sync failed for zone {$zone}, year {$year}: " . $e->getMessage());
            throw $e;
        }
    }
}