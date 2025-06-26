<?php

namespace App\Libraries;

use App\Entities\Coaster;
use CodeIgniter\CLI\CLI;

class ConsoleMonitor
{
    public function print(array $coasters): void
    {
        CLI::clearScreen();
        CLI::newLine(2);
        CLI::print("Coaster Monitor V1.0", 'cyan');
        CLI::newLine(2);

        /** @var Coaster $coaster */
        foreach ($coasters as $coaster) {
            $calculator = new CoasterStatusCalculator();
            $problems = $calculator->problems($coaster);
            $implodedProblems = implode(', ', $problems);
            $status = $problems ? $implodedProblems : 'OK';

            ClI::print(<<< EOT
            [Kolejka {$coaster->getId()}]
            1. Godziny działania: {$coaster->getOperatingHours()->getFrom()} - {$coaster->getOperatingHours()->getTo()}
            2. Liczba wagonów: {$coaster->countWagons()}/{$calculator->requiredWagons($coaster)}
            3. Dostępny personel {$coaster->getStaffCount()}/{$calculator->requiredStaff($coaster)}
            4. Klienci dziennie: {$coaster->getCustomerCount()}
            5. Status: {$status}
            EOT, count($problems) ? 'red' : 'green');
            CLI::newLine(2);
        }
    }
}