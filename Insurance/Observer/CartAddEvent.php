<?php
namespace Yogesh\Insurance\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\ObjectManager;
class CartAddEvent implements ObserverInterface
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
    $this->_dataHelper->log('CartAddEvent');
    $item = $observer->getEvent()->getData('quote_item');
    if ($item && $item->getProduct() && $item->getProduct()->isVirtual()) {
      //I'm adding the Insurance
    } else {
      if ($this->_dataHelper->isProductFree($item)) {
        //It's a free product, ignore it
      } else if ($this->_dataHelper->isProductNoInsurance($item)) {
        //It's a product with no insurance, ignore it
      } else {
		  	/*echo $item->getProduct()->getId(); exit;
		  $objectManager = ObjectManager::getInstance();
		  $product = $objectManager->create('Magento\Catalog\Model\Product')->load($post['product']);  
			$cart = $objectManager->create('Magento\Checkout\Model\Cart'); 
			$options = array();
			$params = array(
				'form_key' => $post['form_key'],
				'product' => $post['product'], 
				'options' => $options,
				'qty'   =>1
			);
				$cart->addProduct($product, $params);
				$cart->save();*/
        $this->_dataHelper->addInsurance();
      }
    }
   }
}