<?php
namespace App\Validation;

use CodeIgniter\I18n\Time;

class DateRules
{
    public function after(string $str, string $fields, array $data): bool
    {
        if (! isset($data[$fields])) {
            return false;
        }

        $start = Time::parse($data[$fields]);
        $end   = Time::parse($str);

        return $end->isAfter($start);
    }
}