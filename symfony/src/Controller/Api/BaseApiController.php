<?php

declare(strict_types=1);

namespace App\Controller\Api;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

abstract class BaseApiController extends AbstractController
{
    protected function getFormErrors(FormInterface $form): array
    {
        $errors = array();
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getFormErrors($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }
        return $errors;
    }

    protected function formErrors(FormInterface $form): JsonResponse
    {
        return $this->errors($this->getFormErrors($form));
    }

    protected function errors(array $errors): JsonResponse
    {
        return $this->json(['errors' => $errors], 400);
    }

    protected function getUser(): User
    {
        /** @var User $user */
        $user = parent::getUser();
        return $user;
    }

    protected function getRequestContentJson(Request $request): array
    {
        $data = json_decode($request->getContent(), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \HttpException(400, 'Invalid json');
        }
        return $data;
    }
}
