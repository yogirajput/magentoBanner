<?php

namespace Yogesh\Cancelorder\Controller\Index;

use Magento\Sales\Api\OrderManagementInterface;
use Magento\Sales\Api\Data\OrderStatusHistoryInterface;

class Index extends \Magento\Framework\App\Action\Action
{

    protected $resultPageFactory;
    /**
     * @var
     */
    private $orderManagement;
    /**
     * @var
     */
    private $commentFactory;

    public function __construct(
        OrderStatusHistoryInterface $commentFactory,
        OrderManagementInterface $orderManagement,
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory)
    {
        $this->commentFactory = $commentFactory;
        $this->orderManagement = $orderManagement;
        $this->resultPageFactory = $resultPageFactory;
        return parent::__construct($context);
    }

    public function execute()
    {
        $params = $this->getRequest()->getParams();
        $orderId = $params['orderId'];
        $comment = $params['comment'];
        try {
            $commentModel = $this->commentFactory->setComment($comment);
            $order = $this->orderManagement;
            $order->cancel($orderId);
            $order->addComment($orderId, $commentModel);
            $order->notify($orderId);
            $this->messageManager->addSuccessMessage("Order Canceled");
            $this->_redirect($this->_redirect->getRefererUrl());
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            $this->_redirect($this->_redirect->getRefererUrl());
        }
//        $this->messageManager->addErrorMessage($e->getMessage());
        $this->_redirect($this->_redirect->getRefererUrl());
//        return $this->resultPageFactory->create();
    }
}
