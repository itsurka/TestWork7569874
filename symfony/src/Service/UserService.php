<?php

declare(strict_types=1);

namespace App\Service;


use App\Entity\User;
use App\Enum\UserStatus;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

class UserService
{
    /** @var UserRepository */
    private $repository;
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->repository = $userRepository;
        $this->entityManager = $entityManager;
    }
    
    /**
     * @param string $key
     */
    public function confirmUserEmailByKey(string $key): User
    {
        $user = $this->repository->findOneBy(['email_confirm_key' => $key]);
        if (!$user) {
            throw new EntityNotFoundException();
        }
        if ($user->getStatus() !== UserStatus::WAIT_EMAIL_CONFIRM) {
            return $user;
        }
        $user->setStatus(UserStatus::EMAIL_CONFIRMED);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}
