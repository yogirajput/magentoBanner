<?php
declare(strict_types=1);

namespace Yogesh\ImportExport\Controller\Adminhtml\Export\File;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\ImportExport\Controller\Adminhtml\Export as ExportController;
use Magento\Framework\Filesystem;
use Magento\ImportExport\Model\LocalizedFileName;
use Throwable;

/**
 * Controller that download file by name.
 */
class Download extends \Magento\ImportExport\Controller\Adminhtml\Export\File\Download
{
    /**
     * Url to this controller
     */
    const URL = 'adminhtml/export_file/download/';

    /**
     * @var FileFactory
     */
    private $fileFactory;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var LocalizedFileName
     */
    private $localizedFileName;

    /**
     * DownloadFile constructor.
     * @param Action\Context $context
     * @param FileFactory $fileFactory
     * @param Filesystem $filesystem
     * @param LocalizedFileName|null $localizedFileName
     */
    public function __construct(
        Action\Context $context,
        FileFactory $fileFactory,
        Filesystem $filesystem,
        ?LocalizedFileName $localizedFileName = null
    ) {
        parent::__construct($context,$fileFactory,$filesystem);
        $this->fileFactory = $fileFactory;
        $this->filesystem = $filesystem;
        $this->localizedFileName = $localizedFileName ?? ObjectManager::getInstance()->get(LocalizedFileName::class);
    }

    /**
     * Controller basic method implementation.
     *
     * @return \Magento\Framework\Controller\Result\Redirect | \Magento\Framework\App\ResponseInterface
     */
    public function execute()
    {

        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('adminhtml/export/index');
        $fileName = $this->getRequest()->getParam('filename');
        
        $exportDirectory = $this->filesystem->getDirectoryRead(DirectoryList::VAR_IMPORT_EXPORT);

        try {
           $fileExist = $exportDirectory->isExist('export/' . $fileName);
        } catch (Throwable $e) {
            $fileExist = false;
        }

        if (empty($fileName) || !$fileExist) {
            $this->messageManager->addErrorMessage(__('Please provide valid export file name'));

            return $resultRedirect;
        }

        try {
           
            $path = 'export/' . $fileName;
            $directory = $this->filesystem->getDirectoryRead(DirectoryList::VAR_IMPORT_EXPORT);
            if ($directory->isFile($path)) {
                return $this->fileFactory->create(
                    $fileName,
                    $directory->readFile($path),
                    DirectoryList::VAR_DIR,
                    'application/octet-stream'
                );

            }
            $this->messageManager->addErrorMessage(__('%1 is not a valid file', $fileName));
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
        }

        return $resultRedirect;
    }
}