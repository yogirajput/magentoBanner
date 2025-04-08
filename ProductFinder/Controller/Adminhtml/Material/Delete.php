<?php
namespace Yogesh\ProductFinder\Controller\Adminhtml\Material;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Yogesh\ProductFinder\Model\MaterialCategory;

class Delete extends Action
{
    /**
     * @var MaterialCategory
     */
    private $materialCategory;

    /**
     * @param Context $context
     * @param MaterialCategory $materialCategory
     */
    public function __construct(
        Context $context,
        MaterialCategory $materialCategory
    ) {
        parent::__construct($context);
        $this->materialCategory = $materialCategory;
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return true;
    }

    /**
     * Delete action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                $this->materialCategory->load($id)->delete();
                $this->messageManager->addSuccess(__('Material Category deleted successfully.'));
                return $resultRedirect->setPath('*/*/');
            } catch (Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }
        $this->messageManager->addError(__('Material Category does not exist.'));
        return $resultRedirect->setPath('*/*/');
    }
}