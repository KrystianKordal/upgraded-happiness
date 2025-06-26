<?php

namespace App\Libraries;

use App\Entities\Coaster;

class CoasterStatusCalculator
{
    public function requiredWagons(Coaster $coaster): int
    {
        $oneWagonRouteTime = $coaster->getRouteLength() / $coaster->getWagonSpeed();
        $routeTimeWithBreak = $oneWagonRouteTime + 5 * 60;
        $operatingTimeInSeconds = $coaster->getOperatingHours()->getTimeInSeconds() + 5 * 60; // After last ride break is not necessary

        $ridesCountForOneWagon = floor($operatingTimeInSeconds / $routeTimeWithBreak);

        $requiredRidesForAllCustomers = ceil($coaster->getCustomerCount() / $coaster->getWagonSeatsCount());

        return ceil($requiredRidesForAllCustomers / $ridesCountForOneWagon);
    }

    public function requiredStaff(Coaster $coaster): int
    {
        return $coaster->countWagons() * 2 + 1;
    }

    public function calculateCapacity(Coaster $coaster): int
    {
        $oneWagonRouteTime = $coaster->getRouteLength() / $coaster->getWagonSpeed();
        $routeTimeWithBreak = $oneWagonRouteTime + 5 * 60;
        $operatingTimeInSeconds = $coaster->getOperatingHours()->getTimeInSeconds() + 5 * 60; // After last ride break is not necessary

        $ridesCountForOneWagon = floor($operatingTimeInSeconds / $routeTimeWithBreak);

        return $ridesCountForOneWagon * $coaster->countWagons() * $coaster->getWagonSeatsCount();
    }

    public function problems(Coaster $coaster): array
    {
        $problems = [];

        $staffDiff = $coaster->getStaffCount() - $this->requiredStaff($coaster);
        $wagonDiff = $coaster->countWagons() - $this->requiredWagons($coaster);

        if ($this->calculateCapacity($coaster) > $coaster->getCustomerCount() * 2) {
            $problems[] = sprintf('Nadmiar wagonów: %s oraz pracowników: %s', $wagonDiff, $wagonDiff * 2 + $staffDiff);
        } elseif ($staffDiff > 0) {
            $problems[] = sprintf("Nadmiar pracowników: %d", $staffDiff);
        } elseif ($staffDiff < 0) {
            $problems[] = sprintf("Brakuje pracowników: %d", abs($staffDiff));
        }

        if ($wagonDiff < 0) {
            $problems[] = sprintf('Brakuje %s wagonów', abs($wagonDiff));
        }

        return $problems;
    }
}