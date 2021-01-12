<?php

namespace App\Controller\Frontend;

use App\Doctrine\Paginator;
use App\Exceptions\PageNotFoundException;
use App\Repository\CarRepository;
use App\Service\PaginatorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CarsController extends AbstractController
{
    /** @var CarRepository */
    private $carRepository;

    public function __construct(CarRepository $carRepository)
    {
        $this->carRepository = $carRepository;
    }

    /**
     * @Route("/", name="main")
     *
     * @param Request $request
     * @param PaginatorService $paginatorService
     * @param CarRepository $carRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, PaginatorService $paginatorService)
    {
        $paginator = $paginatorService->paginate(
            $this->carRepository->getList(['published' => true]),
            $this->carRepository->getList(['published' => true], true),
            $request
        );

        return $this->render('frontend/cars/index.html.twig', [
            'paginator' => $paginator,
        ]);
    }

    /**
     * @Route("/cars/view/{id}", name="view_car")
     */
    public function view(Request $request)
    {
        $id = $request->get('id');
        if (!$id || !$car = $this->carRepository->getPublishedById($id)) {
            throw new PageNotFoundException();
        }

        return $this->render('frontend/cars/view.html.twig', [
            'car' => $car,
        ]);
    }
}
