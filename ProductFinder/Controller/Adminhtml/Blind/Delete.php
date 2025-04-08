<?php
namespace Yogesh\ProductFinder\Controller\Adminhtml\Blind;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Yogesh\ProductFinder\Model\BlindCategory;

class Delete extends Action
{
    /**
     * @var BlindCategory
     */
    private $blindCategory;

    /**
     * @param Context $context
     * @param BlindCategory $blindCategory
     */
    public function __construct(
        Context $context,
        BlindCategory $blindCategory
    ) {
        parent::__construct($context);
        $this->blindCategory = $blindCategory;
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
                $this->blindCategory->load($id)->delete();
                $this->messageManager->addSuccess(__('Blind Type deleted successfully.'));
                return $resultRedirect->setPath('*/*/');
            } catch (Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }
        $this->messageManager->addError(__('Room Type does not exist.'));
        return $resultRedirect->setPath('*/*/');
    }
}