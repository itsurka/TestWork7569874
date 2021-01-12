<?php

declare(strict_types=1);

namespace App\Controller\Api;


use App\Form\ChunkFileType;
use App\Form\UploadedFileType;
use App\Service\FileUploadServiceFactory;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class FilesController
 * @package App\Controller\Api
 */
class FilesController extends BaseApiController
{
    /** @var LoggerInterface */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @Route("/api/profile/files/{scope}", name="api_profile_files_create", methods={"PUT"})
     *
     * @param string $scope
     * @param Request $request
     * @param FileUploadServiceFactory $fileUploadServiceFactory
     */
    public function chunked(string $scope, Request $request, FileUploadServiceFactory $fileUploadServiceFactory)
    {
        $form = $this->createForm(ChunkFileType::class);

        $data = [
            'content_length' => $request->headers->get('Content-Length'),
            'content_range' => $request->headers->get('Content-Range'),
            'content_type' => $request->headers->get('Content-Type'),
            'file_name' => $request->headers->get('File-Name'),
        ];
        $form->submit($data);
        if (!$form->isValid()) {
            return $this->formErrors($form);
        }

        $uploader = $fileUploadServiceFactory->create($scope);

        try {
            $file = $uploader->handleChunkedFile(
                $data['content_range'],
                $data['content_type'],
                $data['file_name'],
                $request->getContent(),
                $this->getUser(),
            );
        } catch (\Throwable $throwable) {
            $this->logger->error('Chunked file upload failed', [
                'data' => $data,
                'error' => $throwable->getMessage(),
            ]);
            throw new \Exception('Save file error');
        }

        return $this->json([
            'file_id' => $file ? $file->getId() : null,
        ]);
    }

    /**
     * @Route("/api/files/{scope}", name="api_files_create", methods={"POST"})
     *
     * @param string $scope
     * @param Request $request
     * @param FileUploadServiceFactory $fileUploadServiceFactory
     */
    public function upload(string $scope, Request $request, FileUploadServiceFactory $fileUploadServiceFactory)
    {
        $form = $this->createForm(UploadedFileType::class);
        $form->submit($request->files->all());

        if ($form->isSubmitted() && $form->isValid()) {
            $uploader = $fileUploadServiceFactory->create($scope);

            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form->get('file')->getData();
            $fileId = $uploader->handleUploadedFile($uploadedFile);

            return new JsonResponse(['success' => true, 'file_id' => $fileId]);
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        return new JsonResponse(['success' => false, 'errors' => $errors], 400);
    }
}
