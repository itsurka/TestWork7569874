<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Enum\CarBody;
use App\Enum\CarFuel;
use App\Enum\CarGearbox;
use App\Enum\CarWheelDrive;
use App\Repository\CarBrandModelRepository;
use App\Repository\CarBrandRepository;
use App\Repository\CityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SiteController extends BaseApiController
{
    /** @var CarBrandRepository */
    private $carBrandRepository;

    /** @var CarBrandModelRepository */
    private $carBrandModelRepository;

    /** @var CityRepository */
    private $cityRepository;

    public function __construct(
        CarBrandRepository $carBrandRepository,
        CarBrandModelRepository $carBrandModelRepository,
        CityRepository $cityRepository
    ) {
        $this->carBrandRepository = $carBrandRepository;
        $this->carBrandModelRepository = $carBrandModelRepository;
        $this->cityRepository = $cityRepository;
    }

    /**
     * @Route("/api/site/common", name="site_common_get", methods={"GET"})
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function commonData(Request $request)
    {
        return $this->json([
            'brands' => $this->getBrandsList(),
            'brand_models' => $this->getBrandModelsList(),
            'gearbox_types' => CarGearbox::getList(),
            'body_types' => CarBody::getList(),
            'fuel_types' => CarFuel::getList(),
            'wheel_drive_types' => CarWheelDrive::getList(),
            'cities' => $this->getCityList(),
        ]);
    }

    private function getBrandsList(): array
    {
        $result = [];
        $brands = $this->carBrandRepository->findAll();
        foreach ($brands as $brand) {
            $result[$brand->getId()] = [
                'id' => $brand->getId(),
                'title' => $brand->getTitle(),
            ];
        }
        return $result;
    }

    private function getBrandModelsList(): array
    {
        $result = [];
        $models = $this->carBrandModelRepository->findAll();
        foreach ($models as $model) {
            $result[$model->getId()] = [
                'id' => $model->getId(),
                'title' => $model->getTitle(),
                'brand_id' => $model->getCarBrand()->getId(),
            ];
        }
        return $result;
    }

    private function getCityList(): array
    {
        $result = [];
        $cities = $this->cityRepository->findAll();
        foreach ($cities as $city) {
            $result[] = [
                'id' => $city->getId(),
                'title' => $city->getTitle(),
            ];
        }
        return $result;
    }
}