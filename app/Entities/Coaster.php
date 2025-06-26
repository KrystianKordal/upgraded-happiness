<?php

namespace App\Entities;

use App\ValueObjects\OperatingHours;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Coaster
{
    private array $wagons = [];

    public static function restore(array $data): self
    {
        $instance = new self(
            id: Uuid::fromString($data['id']),
            routeLength: $data['routeLength'],
            staffCount: $data['staffCount'],
            customerCount: $data['customerCount'],
            operatingHours: new OperatingHours($data['operatingHours']['from'], $data['operatingHours']['to']),
        );

        foreach ($data['wagons'] as $wagon) {
            $instance->addWagon(Wagon::restore($wagon));
        }

        return $instance;
    }

    public static function create(
        int $customerCount,
        int $routeLength,
        int $staffCount,
        operatingHours $operatingHours,
        ?UuidInterface $id = null,
    ): self {
        return new self(
            id: $id ?? Uuid::uuid4(),
            routeLength: $routeLength,
            staffCount: $staffCount,
            customerCount: $customerCount,
            operatingHours: $operatingHours
        );
    }

    protected function __construct(
        private readonly UuidInterface $id,
        private readonly int $routeLength,
        private int $staffCount,
        private int $customerCount,
        private operatingHours $operatingHours,
    ) {
    }

    public function setCustomerCount(int $customerCount): void
    {
        $this->customerCount = $customerCount;
    }

    public function setOperatingHours(OperatingHours $operatingHours): void
    {
        $this->operatingHours = $operatingHours;
    }

    public function setStaffCount(int $staffCount): void
    {
        $this->staffCount = $staffCount;
    }

    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    public function getCustomerCount(): int
    {
        return $this->customerCount;
    }

    public function getOperatingHours(): OperatingHours
    {
        return $this->operatingHours;
    }


    public function getRouteLength(): int
    {
        return $this->routeLength;
    }

    public function getStaffCount(): int
    {
        return $this->staffCount;
    }

    public function toArray(): array
    {
        $wagonsArray = [];

        foreach ($this->wagons as $wagon) {
            $wagonsArray[] = $wagon->toArray();
        }
        return [
            'id' => $this->id->toString(),
            'staffCount' => $this->staffCount,
            'customerCount' => $this->customerCount,
            'routeLength' => $this->routeLength,
            'operatingHours' => [
                'from' => $this->operatingHours->getFrom(),
                'to' => $this->operatingHours->getTo(),
            ],
            'wagons' => $wagonsArray,
        ];
    }

    public function addWagon(Wagon $wagon): void
    {
        $this->wagons[$wagon->getId()->toString()] = $wagon;
    }

    public function removeWagon(UuidInterface $wagonId): void
    {
        unset($this->wagons[$wagonId->toString()]);
    }

    public function countWagons(): int
    {
        return count($this->wagons);
    }

    /**
     * Taki mały hack, żebym mógł wyliczać ilość potrzebnych wagonów. Sorka :>
     */
    public function getWagonSeatsCount(): int
    {
        return isset($this->wagons[0]) ? $this->wagons[0]->getSeats() : 32;
    }

    /**
     * Tutaj też :P
     */
    public function getWagonSpeed(): float
    {
        return isset($this->wagons[0]) ? $this->wagons[0]->getSpeed() : 1.2;

    }
}