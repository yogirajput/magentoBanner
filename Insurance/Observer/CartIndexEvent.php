<?php
namespace Yogesh\Insurance\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Checkout\Model\Session;

class CartIndexEvent implements ObserverInterface
{
    protected $_dataHelper;

    public function __construct(
        \Yogesh\Insurance\Helper\Data $dataHelper
    ){
        $this->_dataHelper = $dataHelper;
    }

   /**
    * Below is the method that will fire whenever the event runs!
    *
    * @param Observer $observer
    */
   public function execute(Observer $observer)
   {
    $this->_dataHelper->log('CartIndexEvent');
    $this->_dataHelper->addInsurance();    
   }
}