<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Yogesh\Feature\Controller\Adminhtml\feature;

class Index extends \Magento\Backend\App\Action {

    protected $resultPageFactory = false;

    public function __construct(
    \Magento\Backend\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute() {
        //Call page factory to render layout and page content
        $resultPage = $this->resultPageFactory->create();

        //Set the menu which will be active for this page
        $resultPage->setActiveMenu('Appnova_Feature::feature_manage');

        //Set the header title of grid
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Feature'));

        //Add bread crumb
        $resultPage->addBreadcrumb(__('Feature'), __('Feature'));

        return $resultPage;
    }

    /*
     * Check permission via ACL resource
     */

    protected function _isAllowed() {
        return $this->_authorization->isAllowed('Appnova_Feature::feature_manage');
    }

}