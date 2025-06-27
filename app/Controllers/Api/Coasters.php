<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Entities\Coaster;
use App\Entities\Wagon;
use App\ValueObjects\OperatingHours;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Ramsey\Uuid\Uuid;

class Coasters extends BaseController
{
    use ResponseTrait;

    public function add(): ResponseInterface
    {
        $coasterRepository = service('coasterRepository');
        $data = $this->request->getJSON(true);

        $coaster = Coaster::create(
            customerCount: $data['liczba_klientow'],
            routeLength: $data['dl_trasy'],
            staffCount: $data['liczba_personelu'],
            operatingHours: new OperatingHours(
                $data['godziny_od'],
                $data['godziny_do']
            )
        );

        $coasterRepository->add($coaster);

        return $this->respondCreated(['id' => $coaster->getId()]);
    }

    public function update(string $id): ResponseInterface
    {
        $id = Uuid::fromString($id);
        $coasterRepository = Services::coasterRepository();
        $data = $this->request->getJSON(true);

        $coaster = $coasterRepository->find($id);

        if (!$coaster) {
            return $this->failNotFound('Coaster not found');
        }

        $operatingHours = new OperatingHours(
            $data['godziny_od'] ?? $coaster->getOperatingHours()->getFrom(),
            $data['godziny_do'] ?? $coaster->getOperatingHours()->getTo(),
        );

        $coaster->setStaffCount($data['liczba_personelu'] ?? $coaster->getStaffCount());
        $coaster->setCustomerCount($data['liczba_klientow'] ?? $coaster->getCustomerCount());
        $coaster->setOperatingHours($operatingHours);

        $coasterRepository->update($coaster);

        return $this->respondUpdated([
            'id' => $coaster->getId()
        ]);
    }

    public function delete(string $coasterId): ResponseInterface
    {
        $id = Uuid::fromString($coasterId);
        $coasterRepository = Services::coasterRepository();

        $coaster = $coasterRepository->find($id);

        if (!$coaster) {
            return $this->failNotFound('Coaster not found');
        }

        $coasterRepository->delete($coaster);

        return $this->respondDeleted();
    }

    public function addWagon(string $coasterId): ResponseInterface
    {
        $coasterId = Uuid::fromString($coasterId);
        $coasterRepository = Services::coasterRepository();
        $data = $this->request->getJSON(true);

        $coaster = $coasterRepository->find($coasterId);

        if (!$coaster) {
            return $this->failNotFound('Coaster not found');
        }

        $coaster->addWagon(
            Wagon::create(
                seats: $data['ilosc_miejsc'],
                speed: $data['predkosc_wagonu'],
            )
        );

        $coasterRepository->update($coaster);

        return $this->respondCreated(['id' => Uuid::uuid4()->toString()]);
    }

    public function deleteWagon(string $coasterId, string $wagonId): ResponseInterface
    {
        $coasterId = Uuid::fromString($coasterId);
        $wagonId = Uuid::fromString($wagonId);
        $coasterRepository = Services::coasterRepository();

        $coaster = $coasterRepository->find($coasterId);

        if (!$coaster) {
            return $this->failNotFound('Coaster not found');
        }

        $coaster->removeWagon($wagonId);

        $coasterRepository->update($coaster);

        return $this->respondDeleted();
    }
}