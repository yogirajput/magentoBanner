<?php
namespace Yogesh\Banner\Controller\Adminhtml\banner;

use Magento\Backend\App\Action;
use Magento\Framework\App\Filesystem\DirectoryList;


class Save extends \Magento\Backend\App\Action
{

    /**
     * @param Action\Context $context
     */
    public function __construct(Action\Context $context)
    {
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
		if(isset($data['stores'])) {
			if(in_array('0',$data['stores'])){
				$data['store_id'] = '0';
			}
			else{
				$data['store_id'] = implode(",", $data['stores']);
			}
		   unset($data['stores']);
		}
		
        if ($data) {
            $model = $this->_objectManager->create('Yogesh\Banner\Model\Banner');

            $id = $this->getRequest()->getParam('banner_id');
            if ($id) {
                $model->load($id);
                $model->setCreatedAt(date('Y-m-d H:i:s'));
            }
			/* Prepare featured image */
			$imageField = 'image';
			$imageMobileField = 'imagemobile';
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
			if (isset($data[$imageMobileField]) && isset($data[$imageMobileField]['value'])) {
				if (isset($data[$imageMobileField]['delete'])) {
					unlink($mediaDirectory->getAbsolutePath() . $data[$imageMobileField]['value']);
					$model->setData($imageMobileField, '');
					$data['imagemobile'] = '';
				} else {
					$model->unsetData($imageMobileField);
					unset($data['imagemobile']);
				}
			}
			try {
				$uploader = $this->_objectManager->create('Magento\MediaStorage\Model\File\UploaderFactory');
				$uploader = $uploader->create(['fileId' => $imageMobileField]);
				$uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
				$uploader->setAllowRenameFiles(true);
				$uploader->setFilesDispersion(true);
				$uploader->setAllowCreateFolders(true);
				$result = $uploader->save(
					$mediaDirectory->getAbsolutePath('yogesh_banner')
				);
				$model->setData($imageMobileField, 'yogesh_banner' . $result['file']);
				$data['imagemobile'] = 'yogesh_banner' . $result['file'];
				$data['imagemobile'] = str_replace('//', '/', $data['imagemobile']);
				
			} catch (\Exception $e) {
				if ($e->getCode() != \Magento\Framework\File\Uploader::TMP_NAME_EMPTY) {
					throw new \Exception($e->getMessage());
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
					$mediaDirectory->getAbsolutePath('yogesh_banner')
				);
				$model->setData($imageField, 'yogesh_banner' . $result['file']);
				$data['image'] = 'yogesh_banner' . $result['file'];
				$data['image'] = str_replace('//', '/', $data['image']);

			} catch (\Exception $e) {
				if ($e->getCode() != \Magento\Framework\File\Uploader::TMP_NAME_EMPTY) {
					throw new \Exception($e->getMessage());
				}
			}
	
            $model->setData($data);

            try {
                $model->save();
                $this->messageManager->addSuccess(__('The Banner has been saved.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['banner_id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the Banner.'));
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['banner_id' => $this->getRequest()->getParam('banner_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
