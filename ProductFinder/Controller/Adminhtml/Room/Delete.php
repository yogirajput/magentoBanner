<?php
namespace Yogesh\ProductFinder\Controller\Adminhtml\Room;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Yogesh\ProductFinder\Model\RoomCategory;

class Delete extends Action
{
    /**
     * @var RoomCategory
     */
    private $roomCategory;

    /**
     * @param Context $context
     * @param RoomCategory $roomCategory
     */
    public function __construct(
        Context $context,
        RoomCategory $roomCategory
    ) {
        parent::__construct($context);
        $this->roomCategory = $roomCategory;
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
                $this->roomCategory->load($id)->delete();
                $this->messageManager->addSuccess(__('Room Type deleted successfully.'));
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