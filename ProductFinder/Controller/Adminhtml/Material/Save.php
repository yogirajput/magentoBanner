<?php

namespace Yogesh\ProductFinder\Controller\Adminhtml\Material;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\Model\Session;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Exception\LocalizedException;
use Yogesh\ProductFinder\Model\MaterialCategory;
use RuntimeException;

class Save extends Action
{
    /**
     * @var Session
     */
    protected $adminsession;

    /**
     * @var MaterialCategory
     */
    private $materialCategory;

    /**
     * @param Action\Context $context
     * @param MaterialCategory $materialCategory
     * @param Session $adminsession
     */
    public function __construct(
        Action\Context $context,
        MaterialCategory   $materialCategory,
        Session        $adminsession
    )
    {
        parent::__construct($context);
        $this->adminsession = $adminsession;
        $this->materialCategory = $materialCategory;
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
                $this->materialCategory->load($id);
            }
            if (isset($data['material_icon']) && is_array($data['material_icon'])) {
		    $data['material_icon'] = $data['material_icon'][0]['name'];
		    $data['material_icon'] = str_replace('//', '/', $data['material_icon']);
            } else {
                $data['material_icon'] = '';
            }
            $this->materialCategory->setData($data);
            try {
                $this->materialCategory->save();
                $this->messageManager->addSuccess(__('Material Category has been saved.'));
                $this->adminsession->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    if ($this->getRequest()->getParam('back') == 'add') {
                        return $resultRedirect->setPath('*/*/add');
                    } else {
                        return $resultRedirect->setPath('*/*/edit', ['id' => $this->materialCategory->getId(), '_current' => true]);
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
