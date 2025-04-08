<?php
namespace Yogesh\Multiplesell\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Checkout\Model\Session;

class CartAddEvent implements ObserverInterface
{
    protected $_dataHelper;

    public function __construct(
        \Yogesh\Multiplesell\Helper\Data $dataHelper
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
    $this->_dataHelper->log('CartAddEvent');
    $item = $observer->getEvent()->getData('quote_item');    
    $item = ( $item->getParentItem() ? $item->getParentItem() : $item );
    if ($item && $item->getProduct() && $this->_dataHelper->isFromCrosssell($item)) {
		
      $qty= $item->getQty();
      $newqty = $this->_dataHelper->calculateQuantity($item);
      //Remove crosssell option
      //$this->_dataHelper->unsetCrosssellOption($item);
      if ($newqty < 0) {
        //weird better do nothing
      } else {
        $item->setQty($newqty);
        //$item->getProduct()->setIsSuperMode(true);
      }
     } else {
      //nothing to do
    }
  }
}