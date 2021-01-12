<?php

namespace App\Controller\Api;

use App\Repository\CarBrandRepository;
use App\Repository\CarBrandModelRepository;
use App\Repository\CarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ApiCarsController
 * @package App\Controller\Api
 */
class CarsController extends AbstractController
{
    /** @var CarRepository */
    private $carRepository;

    /** @var CarBrandModelRepository */
    private $carModelRepository;

    /** @var CarBrandRepository */
    private $carBrandRepository;

    public function __construct(
        CarRepository $carRepository,
        CarBrandRepository $carBrandRepository,
        CarBrandModelRepository $carModelRepository
    ) {
        $this->carRepository = $carRepository;
        $this->carBrandRepository = $carBrandRepository;
        $this->carModelRepository = $carModelRepository;
    }

    /**
     * @Route("/api/cars", name="api_car_index", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function index()
    {
        $result = $this->carRepository->getList(['published' => true])->setMaxResults(20)->getQuery()->getResult();

        return new JsonResponse($result);
    }

    /**
     * @Route("/api/cars/brands/{brandId}/models", name="api_car_brand_models", methods={"GET"})
     *
     * @param int $brandId
     */
    public function models(int $brandId)
    {
        $brand = $this->carBrandRepository->find($brandId);
        if (!$brand) {
            return new JsonResponse([]);
        }

        $models = $this->carModelRepository->findBy(['car_brand' => $brand]);
        $data = [];
        foreach ($models as $model) {
            $data[] = [
                'id' => $model->getId(),
                'title' => $model->getTitle(),
            ];
        }

        return new JsonResponse($data);
    }
}
