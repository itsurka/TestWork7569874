<?php


namespace App\Service;


use App\Entity\Car;
use App\Entity\User;
use App\Enum\EntityStatus;
use App\Repository\CarRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CarService
{
    /** @var CarRepository */
    private $carRepository;

    public function __construct(CarRepository $carRepository)
    {
        $this->carRepository = $carRepository;
    }

    /**
     * @param Car $car
     * @param User|null $owner
     */
    public function createCar(Car $car, ?User $owner = null)
    {
        $car->setCreatedAt(new \DateTime());
        $car->setUpdatedAt(new \DateTime());
        $car->setOwner($owner);
        $car->setStatus(EntityStatus::STATUS_NEW);
        $this->carRepository->save($car);
    }
}
