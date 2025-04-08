<?php

namespace Yogesh\ProductFinder\Controller\Adminhtml\Room;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\Model\Session;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Exception\LocalizedException;
use Yogesh\ProductFinder\Model\RoomCategory;
use RuntimeException;

class Save extends Action
{
    /**
     * @var Session
     */
    protected $adminsession;

    /**
     * @var RoomCategory
     */
    private $roomCategory;

    /**
     * @param Action\Context $context
     * @param RoomCategory $roomCategory
     * @param Session $adminsession
     */
    public function __construct(
        Action\Context $context,
        RoomCategory   $roomCategory,
        Session        $adminsession
    )
    {
        parent::__construct($context);
        $this->adminsession = $adminsession;
        $this->roomCategory = $roomCategory;
    }

    /**
     * Save blog record action
     *
     * @return Redirect
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $id = $this->getRequest()->getParam('id');
            if ($id) {
                $this->roomCategory->load($id);
            }
            if (isset($data['room_icon']) && is_array($data['room_icon'])) {
		    $data['room_icon'] = $data['room_icon'][0]['name'];
		    $data['room_icon'] = str_replace('//', '/', $data['room_icon']);
            } else {
                $data['room_icon'] = '';
            }
            //echo '<pre>';print_R($data);exit;
            $this->roomCategory->setData($data);
            try {
                $this->roomCategory->save();
                $this->messageManager->addSuccess(__('Room Type has been saved.'));
                $this->adminsession->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    if ($this->getRequest()->getParam('back') == 'add') {
                        return $resultRedirect->setPath('*/*/add');
                    } else {
                        return $resultRedirect->setPath('*/*/edit', ['id' => $this->roomCategory->getId(), '_current' => true]);
                    }
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the data.'));
            }
            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
