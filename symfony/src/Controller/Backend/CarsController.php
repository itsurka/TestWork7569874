<?php

namespace App\Controller\Backend;

use App\Entity\Car;
use App\Form\CarType;
use App\Repository\CarRepository;
use App\Service\PaginatorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CarsController extends AbstractController
{
    /** @var CarRepository */
    private $carRepository;

    /**
     * CarsController constructor.
     * @param CarRepository $carRepository
     */
    public function __construct(CarRepository $carRepository)
    {
        $this->carRepository = $carRepository;
    }

    /**
     * @Route("/backend/cars", name="cars")
     *
     * @param Request $request
     * @param PaginatorService $paginatorService
     *
     * @return Response
     */
    public function index(Request $request, PaginatorService $paginatorService)
    {
        $paginator = $paginatorService->paginate(
            $this->carRepository->getList(),
            $this->carRepository->getList([], true),
            $request
        );

        return $this->render('backend/cars/index.html.twig', [
            'paginator' => $paginator,
        ]);
    }

    /**
     * @Route("/backend/cars/create", name="create_car")
     *
     * @param Request
     */
    public function create(Request $request)
    {
        $car = new Car();
        $form = $this->createForm(CarType::class, $car);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $car->setCreatedAt(new \DateTime());
            $car->setUpdatedAt(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($car);
            $entityManager->flush();

            return $this->redirectToRoute('create_car');
        }

        return $this->render('backend/cars/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
