<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Car;
use App\Form\CarType;
use App\Repository\CarRepository;
use App\Repository\DataProvider;
use App\Service\CarService;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class ProfileController extends BaseApiController
{
    /**
     * @Route("/api/profile/cars", name="api_cars_create", methods={"POST"})
     *
     * @param Request $request
     * @param CarService $carService
     * @return JsonResponse
     */
    public function addCar(Request $request, CarService $carService): JsonResponse
    {
        $car = new Car();
        $form = $this->createForm(CarType::class, $car);
        $form->submit($this->getRequestContentJson($request));

        if (!$form->isValid()) {
            return $this->formErrors($form);
        }

        $carService->createCar($car, $this->getUser());

        return $this->json([
            'car' => [
                'id' => $car->getId(),
            ],
        ]);
    }

    /**
     * @Route("/api/profile/cars", name="api_profile_cars_index", methods={"GET"})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function cars(Request $request, CarRepository $carRepository): JsonResponse
    {
        $query = $carRepository->search(
            ['owner' => $this->getUser(),],
            [
                'relations' => [
                    ['join' => 'c.brand', 'alias' => 'b', 'condition_type' => Join::WITH],
                    ['join' => 'c.brand_model', 'alias' => 'bm', 'condition_type' => Join::WITH],
                ]
            ]
        );
        $currentPage = (int)$request->get('page', 1);
        $dataProvider = new DataProvider($query, $currentPage);

        /** @var Car $car */
        $cars = [];
        foreach ($dataProvider->getItems() as $car) {
            $cars[] = [
                'id' => $car->getId(),
                'title' => $car->getTitle(),
                'description' => $car->getDescription(),
                'brand' => [
                    'id' => $car->getBrand()->getId(),
                    'title' => $car->getBrand()->getTitle(),
                ],
                'brand_model' => [
                    'id' => $car->getBrandModel()->getId(),
                    'title' => $car->getBrandModel()->getTitle(),
                    'brand_id' => $car->getBrandModel()->getCarBrand()->getId(),
                ],
            ];
        }

        return $this->json([
            'items' => $cars,
            'current_page' => $currentPage,
            'page_count' => $dataProvider->getPageCount(),
        ]);
    }
}
