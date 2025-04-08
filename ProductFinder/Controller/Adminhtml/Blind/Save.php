<?php

namespace Yogesh\ProductFinder\Controller\Adminhtml\Blind;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\Model\Session;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Exception\LocalizedException;
use Yogesh\ProductFinder\Model\BlindCategory;
use RuntimeException;

class Save extends Action
{
    /**
     * @var Session
     */
    protected $adminsession;

    /**
     * @var BlindCategory
     */
    private $blindCategory;

    /**
     * @param Action\Context $context
     * @param BlindCategory $blindCategory
     * @param Session $adminsession
     */
    public function __construct(
        Action\Context $context,
        BlindCategory   $blindCategory,
        Session        $adminsession
    )
    {
        parent::__construct($context);
        $this->adminsession = $adminsession;
        $this->blindCategory = $blindCategory;
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
                $this->blindCategory->load($id);
            }
            if (isset($data['blind_icon']) && is_array($data['blind_icon'])) {
		    $data['blind_icon'] = $data['blind_icon'][0]['name'];
		    $data['blind_icon'] = str_replace('//', '/', $data['blind_icon']);
            } else {
                $data['blind_icon'] = '';
            }
            $this->blindCategory->setData($data);
            try {
                $this->blindCategory->save();
                $this->messageManager->addSuccess(__('Room Type has been saved.'));
                $this->adminsession->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    if ($this->getRequest()->getParam('back') == 'add') {
                        return $resultRedirect->setPath('*/*/add');
                    } else {
                        return $resultRedirect->setPath('*/*/edit', ['id' => $this->blindCategory->getId(), '_current' => true]);
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
