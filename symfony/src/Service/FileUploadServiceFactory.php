<?php

declare(strict_types=1);

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class UploadFileServiceBuilder
 * @package App\Service
 */
class FileUploadServiceFactory
{
    /** @var ParameterBagInterface */
    private $parameterBag;

    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(ParameterBagInterface $parameterBag, EntityManagerInterface $entityManager)
    {
        $this->parameterBag = $parameterBag;
        $this->entityManager = $entityManager;
    }

    public function create(string $scope): FileUploadService
    {
        switch ($scope) {
            case 'cars':
                $folder = '/cars';
                break;
            case 'default':
                $folder = '';
                break;
            default:
                throw new \Exception('Unknown scope: ' . $scope);
        }

        $dir = $this->parameterBag->get('upload.dir') . $folder;
        $webPath = $this->parameterBag->get('upload.web.path') . $folder;

        return new FileUploadService($dir, $webPath, $this->entityManager);
    }
}
