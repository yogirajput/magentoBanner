<?php
namespace Yogesh\Insurance\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Checkout\Model\Session;

class CartRemoveEvent implements ObserverInterface
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
    $this->_dataHelper->log('CartRemoveEvent');
    $item = $observer->getEvent()->getData('quote_item');
    if ($item && $item->getProduct() && $item->getProduct()->isVirtual()) {		
      $this->_dataHelper->removeFromSession(); //I'm removing the Insurance, to not re-add it after
    } else {		
	if($item->getProduct()->getId() == 2711){
		$this->_dataHelper->removeInsurancecart($item->getId());
	}else{
      //$this->_dataHelper->removeIfNone($item->getId());   
	}
    }
   }
}