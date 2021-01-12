<?php

declare(strict_types=1);

namespace App\Service;


use App\Entity\AccessToken;
use App\Entity\User;
use App\Repository\AccessTokenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Nonstandard\Uuid;

class AuthService
{
    /** @var EntityManagerInterface */
    private $entityManager;
    /** @var AccessTokenRepository */
    private $accessTokenRepository;

    public function __construct(EntityManagerInterface $entityManager, AccessTokenRepository $accessTokenRepository)
    {
        $this->entityManager = $entityManager;
        $this->accessTokenRepository = $accessTokenRepository;
    }

    public function createAccessToken(User $user): AccessToken
    {
        $this->entityManager->beginTransaction();
        try {
            /** @var AccessToken[] $tokens */
            $tokens = $this->accessTokenRepository->findBy(['owner' => $user]);
            foreach ($tokens as $token) {
                $this->entityManager->remove($token);
            }

            $accessToken = $this->createAccessTokenEntity($user);
            $this->entityManager->persist($accessToken);

            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Throwable $throwable) {
            $this->entityManager->rollback();
            throw $throwable;
        }

        return $accessToken;
    }

    private function createAccessTokenEntity(User $user): AccessToken
    {
        $accessToken = new AccessToken();
        $accessToken->setOwner($user);
        $accessToken->setToken(Uuid::uuid4()->toString());
        $accessToken->setCreatedAt(new \DateTime());

        return $accessToken;
    }
}