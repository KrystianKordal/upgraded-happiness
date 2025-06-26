<?php

namespace App\Entities;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Wagon
{
    public static function create(
        int $seats,
        int $speed,
        ?UuidInterface $id = null,
    ): self
    {
        return new self(
            id: $id ?? Uuid::uuid4(),
            seats: $seats,
            speed: $speed
        );
    }

    public static function restore(array $data): self
    {
        return new self(
            id: Uuid::fromString($data['id']),
            seats: $data['seats'],
            speed: $data['speed'],
        );
    }

    protected function __construct(
        private UuidInterface $id,
        private int $seats,
        private float $speed,
    )
    {
    }

    public function getSeats(): int
    {
        return $this->seats;
    }

    public function setSeats(int $seats): void
    {
        $this->seats = $seats;
    }

    public function getSpeed(): float
    {
        return $this->speed;
    }

    public function setSpeed(float $speed): void
    {
        $this->speed = $speed;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

}