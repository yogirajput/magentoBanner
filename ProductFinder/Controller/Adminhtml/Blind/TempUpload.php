<?php
namespace Yogesh\ProductFinder\Controller\Adminhtml\Blind;


use Exception;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Yogesh\ProductFinder\Model\ImageUploader;

class TempUpload extends Action {
    /**
     * @var ImageUploader
     */
    private $imageUploader;

    /**
     * TempUpload constructor.
     * @param Context $context
     * @param ImageUploader $imageUploader
     */
    public function __construct(
        Context $context,
        ImageUploader $imageUploader
    )
    {
        parent::__construct($context);
        $this->imageUploader = $imageUploader;
    }

    public function execute()
    {
        try {
            /**
             * @var ImageUploader $imageUploader
             */
            $imageUploader = $this->imageUploader;
            $imageUploader->setBasePath("blind_icon/icon");
            $imageUploader->setBaseTmpPath("blind_icon/icon");
            $result = $imageUploader->saveFileToTmpDir('blind_icon');
            $result['cookie'] = [
                'name' => $this->_getSession()->getName(),
                'value' => $this->_getSession()->getSessionId(),
                'lifetime' => $this->_getSession()->getCookieLifetime(),
                'path' => $this->_getSession()->getCookiePath(),
                'domain' => $this->_getSession()->getCookieDomain(),
            ];
        } catch (Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}