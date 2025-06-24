<?php


namespace App\Command;

use App\Entity\JourFerie;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:import:jours-feries',
    description: 'Import holidays from CSV file'
)]
class ImportJourFerieCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private string $projectDir
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument(
            'file',
            InputArgument::OPTIONAL,
            'CSV file path',
             'conception/ApiJourFerie/jours_feries_metropole.csv' // Updated path
        );
    }

  protected function execute(InputInterface $input, OutputInterface $output): int
{
    $filePath = $input->getArgument('file');
    
    if (!file_exists($filePath)) {
        $filePath = $this->projectDir.'/'.$filePath;
    }

    try {
        $csv = Reader::createFromPath($filePath);
        $csv->setDelimiter(',');
        $csv->setHeaderOffset(0);

        $requiredColumns = ['date', 'annee', 'zone', 'nom_jour_ferie'];
        $headers = $csv->getHeader();
        
        foreach ($requiredColumns as $col) {
            if (!in_array($col, $headers)) {
                throw new \RuntimeException("Missing required column: $col");
            }
        }

        $imported = 0;
        foreach ($csv as $record) {
            try {
                $date = \DateTime::createFromFormat('Y-m-d', $record['date']);
                if (!$date) {
                    throw new \RuntimeException("Invalid date format: {$record['date']}");
                }

                $existing = $this->em->getRepository(JourFerie::class)->findOneBy([
                    'date' => $date,
                    'zone' => $record['zone']
                ]);

                if (!$existing) {
                    $jourFerie = new JourFerie();
                    $jourFerie->setDate($date)
                        ->setNom($record['nom_jour_ferie'])
                        ->setZone($record['zone'])
                        ->setAnnee((int)$record['annee']);

                    $this->em->persist($jourFerie);
                    $imported++;
                    
                    if ($output->isVerbose()) {
                        $output->writeln("Importing: {$date->format('Y-m-d')} - {$record['nom_jour_ferie']}");
                    }
                }
            } catch (\Exception $e) {
                $output->writeln("<error>Error processing record: {$e->getMessage()}</error>");
                if ($output->isVerbose()) {
                    $output->writeln("<comment>Problematic record: ".json_encode($record)."</comment>");
                }
            }
        }

        $this->em->flush();
        $output->writeln("<info>Successfully imported {$imported} new holidays</info>");
        return Command::SUCCESS;

    } catch (\Exception $e) {
        $output->writeln("<error>Import failed: {$e->getMessage()}</error>");
        return Command::FAILURE;
    }
}
}