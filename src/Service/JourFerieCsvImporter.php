<?php
// src/Service/JourFerieCsvImporter.php
namespace App\Service;

use App\Entity\JourFerie;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;

class JourFerieCsvImporter
{
    public function __construct(
        private EntityManagerInterface $em,
        private string $projectDir
    ) {}

    public function import(string $filePath): int
    {
        $csv = Reader::createFromPath($filePath);
        $csv->setDelimiter(';');
        $csv->setHeaderOffset(0);

        $imported = 0;
        foreach ($csv as $record) {
            $date = \DateTime::createFromFormat('Y-m-d', $record['date']);
            if (!$date) continue;

            $existing = $this->em->getRepository(JourFerie::class)->findOneBy([
                'date' => $date,
                'zone' => $record['zone'] ?? 'metropole'
            ]);

            if (!$existing) {
                $jourFerie = new JourFerie();
                $jourFerie->setDate($date)
                    ->setNom($record['nom'])
                    ->setZone($record['zone'] ?? 'metropole');
                $this->em->persist($jourFerie);
                $imported++;
            }
        }

        $this->em->flush();
        return $imported;
    }
}