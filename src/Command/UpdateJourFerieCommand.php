<?php


// src/Command/UpdateJourFerieCommand.php
namespace App\Command;

use App\Message\SyncHolidaysMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'app:update:jours-feries',
    description: 'Update holidays from public API'
)]
class UpdateJourFerieCommand extends Command
{
    public function __construct(
        private MessageBusInterface $bus,
        private LoggerInterface $logger
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('year', InputArgument::OPTIONAL, 'Year to update', date('Y'))
            ->addOption(
                'all-zones', 
                null, 
                InputOption::VALUE_NONE, 
                'Update all zones (default: metropole only)'
            )
            ->addOption(
                'force',
                'f',
                InputOption::VALUE_NONE,
                'Force update even if holidays already exist'
            )
            ->addOption(
                'range',
                'r',
                InputOption::VALUE_REQUIRED,
                'Year range (e.g., "2023-2025")'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $force = $input->getOption('force');
        $zones = $input->getOption('all-zones') 
            ? $this->getAllZones() 
            : ['metropole'];

        if ($range = $input->getOption('range')) {
            return $this->handleYearRange($range, $zones, $force, $output);
        }

        $year = $input->getArgument('year');
        return $this->updateForYear($year, $zones, $force, $output);
    }

    private function handleYearRange(
        string $range, 
        array $zones, 
        bool $force,
        OutputInterface $output
    ): int {
        if (!preg_match('/^(\d{4})-(\d{4})$/', $range, $matches)) {
            $output->writeln('<error>Invalid range format. Use YYYY-YYYY</error>');
            return Command::FAILURE;
        }

        $start = (int)$matches[1];
        $end = (int)$matches[2];

        if ($start > $end) {
            $output->writeln('<error>Start year must be before end year</error>');
            return Command::FAILURE;
        }

        for ($year = $start; $year <= $end; $year++) {
            $result = $this->updateForYear($year, $zones, $force, $output);
            if ($result !== Command::SUCCESS) {
                return $result;
            }
        }

        return Command::SUCCESS;
    }

    private function updateForYear(
        int $year, 
        array $zones, 
        bool $force,
        OutputInterface $output
    ): int {
        $output->writeln(sprintf(
            '<info>Updating holidays for year %d (%s zones)...</info>',
            $year,
            implode(', ', $zones)
        ));

        foreach ($zones as $zone) {
            try {
                $this->bus->dispatch(new SyncHolidaysMessage($zone, (string)$year, $force));
                $output->writeln(sprintf(
                    '  - Dispatched update for zone: <comment>%s</comment>',
                    $zone
                ));
            } catch (\Exception $e) {
                $this->logger->error(sprintf(
                    'Failed to dispatch update for zone %s, year %d: %s',
                    $zone,
                    $year,
                    $e->getMessage()
                ));
                $output->writeln(sprintf(
                    '<error>Error updating zone %s: %s</error>',
                    $zone,
                    $e->getMessage()
                ));
                return Command::FAILURE;
            }
        }

        return Command::SUCCESS;
    }

    private function getAllZones(): array
    {
        return [
            'metropole',
            'alsace-moselle',
            'guadeloupe',
             'guyane',
             'la-renion',
            'martinique',
            'mayotte',
              'nouvelle-caledonie',
              'polynesie-francaise',
             'saint-barthelemy',
            'saint-martin',
            'saint-pierre-et-miquelon',
            'wallis-et-futuna',
         
        ];
    }
}