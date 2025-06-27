<?php

namespace App\Libraries;

use App\Entities\Coaster;
use CodeIgniter\CLI\CLI;
use Config\Services;

class ConsoleMonitor
{
    public function __construct(
        private readonly CoasterStatusCalculator $calculator,
        private readonly Notifier $notifier
    )
    {
    }

    /**
     * @param Coaster[] $coasters
     */
    public function print(array $coasters): void
    {
        CLI::clearScreen();
        CLI::newLine(2);
        CLI::print("Coaster Monitor V1.0", 'cyan');
        CLI::newLine(2);

        foreach ($coasters as $coaster) {
            $problems = $this->calculator->problems($coaster);

            if ($problems) {
                $this->notifier->logProblem(sprintf('Kolejka %s - Problem: %s', $coaster->getId(), implode(', ', $problems)));
            }

            $status = count($problems) ? implode(', ', $problems) : 'OK';

            ClI::print(
                <<< EOT
                [Kolejka {$coaster->getId()}]
                1. Godziny działania: {$coaster->getOperatingHours()->getFrom()} - {$coaster->getOperatingHours()->getTo()}
                2. Liczba wagonów: {$coaster->countWagons()}/{$this->calculator->requiredWagons($coaster)}
                3. Dostępny personel {$coaster->getStaffCount()}/{$this->calculator->requiredStaff($coaster)}
                4. Klienci dziennie: {$coaster->getCustomerCount()}
                5. Status: {$status}
                EOT,
                count($problems) ? 'red' : 'green'
            );
            CLI::newLine(2);
        }
    }
}