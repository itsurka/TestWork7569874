<?php

namespace App\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController
 * @package App\Controller
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("/backend", name="backend_home")
     */
    public function index()
    {
        return $this->render('backend/default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
}
