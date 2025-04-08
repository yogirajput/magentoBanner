<?php

namespace Yogesh\Feature\Controller\Adminhtml\feature;

use Magento\Backend\App\Action;
use Magento\Framework\App\Filesystem\DirectoryList;

class Save extends \Magento\Backend\App\Action {

    /**
     * @param Action\Context $context
     */
    public function __construct(Action\Context $context) {
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute() {
        $data = $this->getRequest()->getPostValue();
        $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/cmspageSave.log');
        $logger = new \Zend_Log();
        $logger->addWriter($writer);

        $logger->info($data);


        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $model = $this->_objectManager->create('Yogesh\Feature\Model\Feature');

            $id = $this->getRequest()->getParam('id');
            if ($id) {
                $model->load($id);
                $model->setCreatedAt(date('Y-m-d H:i:s'));
            }
			/* Prepare featured image */
			$imageField = 'image';
			$fileSystem = $this->_objectManager->create('Magento\Framework\Filesystem');
			$mediaDirectory = $fileSystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);

			if (isset($data[$imageField]) && isset($data[$imageField]['value'])) {
				if (isset($data[$imageField]['delete'])) {
					unlink($mediaDirectory->getAbsolutePath() . $data[$imageField]['value']);
					$model->setData($imageField, '');
					$data['image'] = '';
				} else {
					$model->unsetData($imageField);
					unset($data['image']);
				}
			}
			try {
				$uploader = $this->_objectManager->create('Magento\MediaStorage\Model\File\UploaderFactory');
				$uploader = $uploader->create(['fileId' => $imageField]);
				$uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
				$uploader->setAllowRenameFiles(true);
				$uploader->setFilesDispersion(true);
				$uploader->setAllowCreateFolders(true);
				$result = $uploader->save(
					$mediaDirectory->getAbsolutePath('appnova_feature')
				);
				$model->setData($imageField, 'appnova_feature' . $result['file']);
				$data['image'] = 'appnova_feature' . $result['file'];
				
			} catch (\Exception $e) {
				if ($e->getCode() != \Magento\Framework\File\Uploader::TMP_NAME_EMPTY) {
					throw new \Exception($e->getMessage());
				}
			}
            
            $model->setData($data);

            try {
                $model->save();
                $this->messageManager->addSuccess(__('The Feature has been saved.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the Feature.'));
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }

}
