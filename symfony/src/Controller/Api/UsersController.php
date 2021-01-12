<?php

declare(strict_types=1);

namespace App\Controller\Api;


use App\Entity\User;
use App\Enum\UserRoles;
use App\Enum\UserStatus;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UsersController extends BaseApiController
{
    /**
     * @Route("/api/users", name="api_users_create", methods={"POST"})
     *
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param EntityManagerInterface $entityManager
     * @param ParameterBagInterface $parameterBag
     * @return JsonResponse
     */
    public function create(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        EntityManagerInterface $entityManager,
        /*MailerInterface $mailer,*/
        ParameterBagInterface $parameterBag,
        LoggerInterface $logger
    ): JsonResponse {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->submit($this->getRequestContentJson($request));
        if (!$form->isValid()) {
            return $this->formErrors($form);
        }

        $entityManager->beginTransaction();
        try {
            $user->setRoles([UserRoles::ROLE_USER => UserRoles::ROLE_USER]);
            $user->setCreatedAt(new \DateTime());
            $user->setUpdatedAt(new \DateTime());
            $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));
            $user->setStatus(UserStatus::WAIT_EMAIL_CONFIRM);
            $user->setEmailConfirmKey(Uuid::uuid4()->toString());

            $entityManager->persist($user);
            $entityManager->flush();

            $confirmationLink = sprintf('%s/#/registration/confirm-email?key=%s', $parameterBag->get('urls.base'), $user->getEmailConfirmKey());
            if (false) {
                $email = (new Email())
                    ->from('admin@autosale.local')
                    ->to($user->getEmail())
                    ->subject('Registration at autosale.local')
                    ->html('Click <a href="' . $confirmationLink . '"></a> to activate your account.');

                $mailer->send($email);
            } else {
                $logger->info('Email confirm registration', [
                    'link' => $confirmationLink,
                ]);
            }

            $entityManager->commit();

            return $this->json([]);
        } catch (\Throwable $throwable) {
            $entityManager->rollback();

            throw $throwable;
        }
    }

    /**
     * @Route("/api/users/confirm-email/{key}", name="api_users_confirm_email", methods={"POST"})
     * @param string $key
     * @param UserService $userService
     * @return JsonResponse
     */
    public function confirmEmail(string $key, UserService $userService): JsonResponse
    {
        try {
            $user = $userService->confirmUserEmailByKey($key);
        } catch (EntityNotFoundException $exception) {
            throw new NotFoundHttpException('User not found');
        }

        return $this->json([
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
            ],
        ]);
    }
}
