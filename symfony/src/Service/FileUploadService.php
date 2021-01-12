<?php

declare(strict_types=1);

namespace App\Service;


use App\Entity\File;
use App\Entity\User;
use App\Enum\FileStatus;
use App\Enum\FileType;
use App\Repository\FileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploadService
{
    const CONTENT_RANGE_REGEXP = '/bytes (\d+)-(\d+)\/(\d+)/';

    /** @var string */
    private $dir;

    /** @var string */
    private $webPath;

    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(string $dir, string $webPath, EntityManagerInterface $entityManager)
    {
        $this->dir = $dir;
        $this->webPath = $webPath;
        $this->entityManager = $entityManager;
    }

    public function handleChunkedFile(
        string $contentRange,
        string $contentType,
        string $fileName,
        $bytes,
        ?User $user
    ): ?File
    {
        $_fileArr = explode('.', $fileName);
        $ext = mb_strtolower(array_pop($_fileArr));
        $rangeData = $this->parseContentRange($contentRange);

        // md5hash_contentlength.ext
        $localFileName = md5(implode(':', [
                $rangeData['length'],
                $contentType,
                $fileName,
                $user ? $user->getId() : 0,
            ])) . '_' . $rangeData['length'] . '.' . $ext;

        $subFolders = $this->getSubFolders($localFileName);
        $this->dir .= '/' . $subFolders;
        $this->webPath .= '/' . $subFolders;
        $this->createDir($this->dir);

        $localFile = $this->dir . '/' . $localFileName;

        if ($rangeData['from'] > 0 && !is_file($localFile)) {
            throw new \Exception('File not found: ' . $localFile);
        }

        $fh = fopen($localFile, 'a+');
        if (!$fh) {
            throw new \Exception('Open file error: ' . $localFile);
        }

        $result = fwrite($fh, $bytes);
        if ($result === false) {
            throw new \Exception('Write to file error: ' . $localFile);
        }
        fclose($fh);

        if ($rangeData['to'] + 2 < $rangeData['length']) { // add some bytes
            return null;
        }

        $fileNamePermanent = md5(implode(':', [
                $rangeData['length'],
                $contentType,
                $fileName,
                $user ? $user->getId() : 0,
            ])) . '.' . $ext;

        if (!rename($localFile, $this->dir . '/' . $fileNamePermanent)) {
            throw new \Exception('Rename file error: ' . $localFile);
        }

        return $this->saveEntity(
            $this->webPath . '/' . $fileNamePermanent,
            $contentType,
            FileType::IMAGE, // todo detect
            FileStatus::UPLOADED,
            $user
        );
    }

    /**
     * @param UploadedFile $uploadedFile
     * @return int
     * @throws \Throwable
     */
    public function handleUploadedFile(UploadedFile $uploadedFile): int
    {
        $ext = $uploadedFile->guessExtension();
        if (!$ext) {
            throw new \Exception('Extension is empty');
        }
        $newFilename = md5(microtime() . random_bytes(5)) . '.' . $ext; // todo Uuid

        $subFolders = $this->getSubFolders($newFilename);
        $this->dir .= '/' . $subFolders;
        $this->webPath .= '/' . $subFolders;

        $this->createDir($this->dir);

        $this->entityManager->beginTransaction();

//        try {
//            $file = new File();
//            $file->setPath($this->webPath . '/' . $newFilename);
//            $file->setMime($uploadedFile->getMimeType());
//            $file->setType(FileType::IMAGE); // todo
//            $file->setStatus(FileStatus::PUBLISHED);
//            // todo set width and height
//            $file->setCreatedAt(new \DateTime());
//            $file->setUpdatedAt(new \DateTime());
//
//            $this->entityManager->persist($file);
//            $this->entityManager->flush();
//
//            $targetFile = $this->dir . '/' . $newFilename;
//            if (!$uploadedFile->move($targetFile)) {
//                throw new \Exception('Can not move uploaded file to: ' . $targetFile);
//            }
//
//            $this->entityManager->commit();
//
//            return $file->getId();
//        } catch (\Throwable $throwable) {
//            // todo log
//            $this->entityManager->rollback();
//            throw $throwable;
//        }
        $file = $this->saveEntity(
            $this->webPath . '/' . $newFilename,
            $uploadedFile->getMimeType(),
            FileType::IMAGE,
            FileStatus::PUBLISHED,
            null,
        );
    }

    private function getSubFolders(string $text): string
    {
        return implode('/', [
            substr($text, 0, 2),
            substr($text, 2, 2),
            substr($text, 4, 2)
        ]);
    }

    private function createDir(string $dir): void
    {
        if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
            throw new \Exception('Can not create dir: ' . $dir);
        }
    }

    private function parseContentRange(string $range): array
    {
        preg_match(self::CONTENT_RANGE_REGEXP, $range, $matches);

        if (!is_array($matches) || count($matches) !== 4) {
            throw new \Exception('Invalid range: ' . $range);
        }

        return [
            'from' => (int)$matches[1],
            'to' => (int)$matches[2],
            'length' => (int)$matches[3],
        ];
    }

    private function saveEntity(
        string $webPath,
        string $mime,
        int $type,
        int $status,
        ?User $user
    ): File
    {
        $this->entityManager->beginTransaction();
        try {
            $file = new File();
            $file->setPath($webPath);
            $file->setMime($mime);
            $file->setType($type);
            $file->setStatus($status);
            // todo set width and height
            $file->setCreatedAt(new \DateTime());
            $file->setUpdatedAt(new \DateTime());

            $this->entityManager->persist($file);
            $this->entityManager->flush();

            // todo handle uploaded files!
//            $targetFile = $this->dir . '/' . $newFilename;
//            if (!$uploadedFile->move($targetFile)) {
//                throw new \Exception('Can not move uploaded file to: ' . $targetFile);
//            }

            $this->entityManager->commit();

            return $file;
        } catch (\Throwable $throwable) {
            // todo log
            $this->entityManager->rollback();
            throw $throwable;
        }
    }
}