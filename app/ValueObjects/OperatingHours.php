<?php

namespace App\ValueObjects;

use CodeIgniter\I18n\Time;

class OperatingHours
{
    public function __construct(
        protected string $from,
        protected string $to
    )
    {
    }

    public function getFrom(): string
    {
        return $this->from;
    }

    public function getTo(): string
    {
        return $this->to;
    }

    public function getTimeInSeconds(): int
    {
        $from = new \DateTimeImmutable($this->from);
        $to = new \DateTimeImmutable($this->to);

        return $to->getTimestamp() - $from->getTimestamp();
    }
}