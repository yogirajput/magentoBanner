<?php
namespace Yogesh\Faq\Controller\Adminhtml\faq;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Backend\App\Action
{
    /**
     * @var PageFactory
     */
    protected $resultPagee;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Index action
     *
     * @return void
     */
//    public function execute()
//    {
//        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
//        $resultPage = $this->resultPageFactory->create();
//        $resultPage->setActiveMenu('Yogesh_Faq::faq');
//        $resultPage->addBreadcrumb(__('Yogesh'), __('Yogesh'));
//        $resultPage->addBreadcrumb(__('Manage item'), __('Manage Faq'));
//        $resultPage->getConfig()->getTitle()->prepend(__('Manage Faq'));
//
//        return $resultPage;
//    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__("Manage Faq"));
        return $resultPage;
    }
}
?>