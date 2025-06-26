<?php

namespace App\Repositories;

use App\Entities\Coaster;
use Ramsey\Uuid\UuidInterface;

interface CoasterRepositoryInterface
{
    public function add(Coaster $coaster): void;
    public function update(Coaster $coaster): void;
    public function find(UuidInterface $id): ?Coaster;
    public function delete(Coaster $coaster): void;
}