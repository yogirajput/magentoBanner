<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Yogesh\Banner\Controller\Adminhtml\banner;

use Magento\Backend\App\Action;
/**
 * Class MassDisable
 */
class MassDisable extends \Magento\Backend\App\Action
{
   
    /**
     * Execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     * @throws \Magento\Framework\Exception\LocalizedException|\Exception
     */
	public function execute()
    {
        $itemIds = $this->getRequest()->getParam('selected');
		
        if (!is_array($itemIds) || empty($itemIds)) {
            $this->messageManager->addError(__('Please select item(s).'));
        } else {
            try {
                //$status = (int) $this->getRequest()->getParam('status');
				//print_r( $status); exit;
                foreach ($itemIds as $postId) {
                    $post = $this->_objectManager->get('Yogesh\Banner\Model\Banner')->load($postId);
                    $post->setIsActive(2)->save();
                }
                $this->messageManager->addSuccess(
                    __('A total of %1 record(s) have been disabled.', count($itemIds))
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        return $this->resultRedirectFactory->create()->setPath('banner/*/index');
    }
}
