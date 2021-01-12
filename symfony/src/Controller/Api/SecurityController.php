<?php

declare(strict_types=1);

namespace App\Controller\Api;


use App\Entity\User;
use App\Service\AuthService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends BaseApiController
{
    /**
     * @Route("/api/auth/login", name="api_login")
     *
     * @param Request $request
     * @param AuthService $authService
     * @return Response
     * @throws \Throwable
     */
    public function login(Request $request, AuthService $authService): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->json([
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'token' => $authService->createAccessToken($user)->getToken(),
            ]
        ]);
    }
}
