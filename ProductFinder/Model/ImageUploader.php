<?php
namespace Yogesh\ProductFinder\Model;

use Magento\MediaStorage\Helper\File\Storage\Database;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class ImageUploader
{
    /**
     * @var Database
     */
    private $coreFileStorageDatabase;
    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var UploaderFactory
     */
    private $uploaderFactory;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var String|Null
     */
    private $mediaDirectory;

    /**
     * @var String|Null
     */
    private $allowedExtensions;

    public function __construct(
        Database $coreFileStorageDatabase,
        Filesystem $filesystem,
        UploaderFactory $uploaderFactory,
        StoreManagerInterface $storeManager,
        LoggerInterface $logger
    )
    {
        $this->coreFileStorageDatabase = $coreFileStorageDatabase;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
        $this->uploaderFactory = $uploaderFactory;
        $this->storeManager = $storeManager;
        $this->logger = $logger;
        $this->baseTmpPath = "room_type/icon";
        $this->basePath = "room_type/icon";
        $this->allowedExtensions = ['jpg', 'jpeg', 'gif', 'png'];
    }

    public function setBaseTmpPath($baseTmpPath)
    {
        $this->baseTmpPath = $baseTmpPath;
    }

    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;
    }

    public function setAllowedExtensions($allowedExtensions)
    {
        $this->allowedExtensions = $allowedExtensions;
    }

    public function getBaseTmpPath()
    {
        return $this->baseTmpPath;
    }

    public function getBasePath()
    {
        return $this->basePath;
    }

    public function getAllowedExtensions()
    {
        return $this->allowedExtensions;
    }

    public function getFilePath($path, $imageName)
    {
        return rtrim($path, '/') . '/' . ltrim($imageName, '/');
    }

    public function moveFileFromTmp($imageName)
    {
        $baseTmpPath = $this->getBaseTmpPath();
        $basePath = $this->getBasePath();
        $baseImagePath = $this->getFilePath($basePath, $imageName);
        $baseTmpImagePath = $this->getFilePath($baseTmpPath, $imageName);
        try {
            $this->coreFileStorageDatabase->copyFile(
                $baseTmpImagePath,
                $baseImagePath
            );
            $this->mediaDirectory->renameFile(
                $baseTmpImagePath,
                $baseImagePath
            );
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('Something went wrong while saving the file(s).')
            );
        }
        return $imageName;
    }

    public function saveFileToTmpDir($fileId)
    {
        try {
            $baseTmpPath = $this->getBaseTmpPath();
            $uploader = $this->uploaderFactory->create(['fileId' => $fileId]);
            $uploader->setFilesDispersion(true);
            $uploader->setAllowedExtensions($this->getAllowedExtensions());
            $uploader->setAllowRenameFiles(true);
            $result = $uploader->save($this->mediaDirectory->getAbsolutePath($baseTmpPath));
            if (!$result) {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('File can not be saved to the destination folder.')
                );
            }

            $result['tmp_name'] = str_replace('\\', '/', $result['tmp_name']);
            $result['path'] = str_replace('\\', '/', $result['path']);
            $result['url'] = $this->storeManager
                    ->getStore()
                    ->getBaseUrl(
                        \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                    ) . $this->getFilePath($baseTmpPath, $result['file']);
            $result['name'] = $result['file'];
            if (isset($result['file'])) {
                try {
                    $relativePath = rtrim($baseTmpPath, '/') . '/' . ltrim($result['file'], '/');
                    $this->coreFileStorageDatabase->saveFile($relativePath);
                } catch (\Exception $e) {
                    $this->logger->critical($e);
                    throw new \Magento\Framework\Exception\LocalizedException(
                        __('Something went wrong while saving the file(s).')
                    );
                }
            }
        } catch (\Exception $e) {
            echo var_dump($e);exit;
        }

        return $result;
    }
}