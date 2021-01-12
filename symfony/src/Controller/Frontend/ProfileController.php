<?php

namespace App\Controller\Frontend;

use App\Entity\Car;
use App\Form\CarType;
use App\Service\CarService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="frontend_profile")
     */
    public function index()
    {
        return $this->render('frontend/profile/index.html.twig');
    }

    /**
     * @Route("/profile/cars/create", name="profile_add_car")
     *
     * @param Request $request
     * @param CarService $carService
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addCar(Request $request, CarService $carService)
    {
        $car = new Car();
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $carService->createCar($car);

            return $this->redirectToRoute('profile_add_car');
        }

        return $this->render('frontend/profile/cars/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
