<?php

namespace Yogesh\Insurance\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Checkout\Model\Cart;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Model\Session;

class Data extends AbstractHelper
{

	private $cart;
	private $productFactory;
	private $productRepositoryInterface;
	private $checkoutSession = false;
	private $productSku = false;
	private $productId = false;
	private $product = false;
	private $logger;
	private $enableLog = false;

	public function __construct(
		Context $context, 
		Cart $cart, 
		ProductFactory $productFactory,
		ProductRepositoryInterface $productRepositoryInterface,
        Session $checkoutSession
	){
		parent::__construct($context);
		$this->productFactory = $productFactory;
		$this->productRepositoryInterface = $productRepositoryInterface;
		$this->checkoutSession = $checkoutSession;	
		$this->cart = $cart;	
		$this->logger = $context->getLogger();
	}

	public function log($message = '', $error = false) {
		if (!$this->enableLog) {
			return;
		}
		if ($error) {
			$message = '[ERROR] : '.$message;
		} 
		$message = '[INSURANCE] '.$message;
		$this->logger->info($message);
	}

	public function removeFromSession() {
		if ($this->checkoutSession) {
			$this->checkoutSession->setAppnovaInsuranceFlag(false);
			return true;
		}
		return false;
	}

	public function addToSession() {
		if ($this->checkoutSession) {
			$this->checkoutSession->setAppnovaInsuranceFlag(true);
			return true;
		}
		return false;
	}

	public function checkInsurance() {
		if ($this->checkoutSession) {
			$flag = $this->checkoutSession->getAppnovaInsuranceFlag();
			if (true == $flag || 'true' == strtolower((string)$flag) || '1' == ''.$flag) {				
				return true;
			} 
		}
		return false;
	}	

	public function isSkuDefined() {

		$product_sku = $this->scopeConfig->getValue('dotcom_category_settings/insurance_settings/product_sku', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
		if (!$product_sku) {
			return false;
		}
		
		$this->productSku = $product_sku;

		return true;
	}

	public function isSkuValid() {

		//TODO: Try/catch as if product SKU is not valid an exception is fired
		$product = $this->productRepositoryInterface->get($this->productSku);

		if (!$product) {
			return false;
		}

		$this->product = $product;
		$this->productId = $product->getId();

		return true;

	}

	public function isAllOk() {

		if (!$this->isSkuDefined()) {
			return false;
		}

		if (!$this->isSkuValid()) {
			return false;
		}

		return true;		
	}

	public function validProductsInCart() {
		$cart = $this->cart;
		$result = $cart->getItems(); //To get also the not visible ones
		if (count($result)>0) {
			foreach ($result as $cartItem) {
		    	if ($cartItem->getProduct()->getId() == $this->productId) {
					//It's the insurance, skip
					continue;
		    	}
		    	if ($this->isProductFree($cartItem)) {
		    		//It's a free sample, skip
		    		continue;
		    	}
		    	if ($cartItem->getProduct()->isVirtual()) {
		    		//Virtual item, skip
		    		continue;
		    	}
		    	if ($this->isProductNoInsurance($cartItem)) {
		    		//No insurance product
		    		continue;
		    	}
		    	//At least a valid product is in the Cart
		    	return true;
			}
		}
		return false;
	}

	public function isInsuranceInCart() {

		//Check if session insurance flag is set
		if (!$this->checkInsurance()) {
			return false;
		}

		if (!$this->isAllOk()) {
			return false; //Something wrong or sku not valid/set
		}
		$this->log('method check if insurance is in cart');
		$cart = $this->cart;
		$result = $cart->getItems(); //To get also the not visible ones
		if (count($result)>0) {
			foreach ($result as $cartItem) {
		    	if ($cartItem->getProduct()->getId() == $this->productId) {
					$this->log('found insurance in cart');
		    		return true;
		    	}
			}
		}
		return false;
	}

	public function isProductNoInsurance($item = false) {
		if (!$item) {
			return false;
		}
		//echo "insurance";
		//var_dump($item);
		return $item->getProduct()->getAppnovaHideInsurance() == 1;
	}

	public function isProductFree($item = false) {
		if (!$item) {
			return false;
		}
		//echo "free";
		//var_dump($item);
		$options = array();
		$customOptions = $item->getProduct()->getTypeInstance(true)->getOrderOptions($item->getProduct());
		if ($customOptions && count($customOptions)>0) {			
			foreach ($customOptions as $k => $o) {
				if (is_array($o)) {
					foreach ($o as $key => $value) {
						$options[$key] = $value;
					}
				} else {
					$options[$k] = $o;
				}         
			}
		}		  
		return isset($options['Free Sample']) || in_array('Free Sample', $options);
	}

	public function removeIfNone($ignoreId = false) {

		//Check if session insurance flag is set
		if (!$this->checkInsurance()) {
			return;
		}
		//echo $ignoreId;

		if (!$this->isAllOk()) {
			return; //Something wrong or sku not valid/set
		}

		$this->log('method remove if insurance is not in cart');
		$cart = $this->cart;
		//Checking if product is already in cart
		$result = $cart->getItems();
		$delete = true;
		
		$this->log('result: '.count($result));
		if (count($result) > 0) {	
			foreach ($result as $cartItem) { 
		    	if ($cartItem->getProduct()->getId() == $this->productId) {					
		    		//Insurance 
		    		$this->log('insurance found in cart');
					//$this->removeInsurance();
					/*if(count($result) > 2 && (!$this->isProductFree($cartItem) && !$this->isProductNoInsurance($cartItem)) && $cartItem->getProduct()->getId()!=2019){
						
						$delete = false;
					}*/
		    	} else {
		    		if (!$ignoreId || $cartItem->getId() != $ignoreId) {  		
		    			//Not insutance, not empty
		    			if (!$this->isProductFree($cartItem) && !$this->isProductNoInsurance($cartItem)) {
							
		    				$this->log('item found in cart');		    				
							$delete = false;
		    			} else {
		    				$this->log('Free product found, ignore it');
							
		    			}
		    		} else {
		    			//Item ignored as it's going to be deleted
		    			$this->log('this item is going to be deleted');
						/*if(count($result) > 1 && (!$this->isProductFree($cartItem) && !$this->isProductNoInsurance($cartItem))){
							
							$delete = false;
						}*/
						//$this->removeInsurance();
		    		}
		    	}
			}
		}
		
		if ($delete) {
			$this->log('no valid items in cart but insurance');
			$this->removeInsurance();
		} else {
			$this->log('still items in cart, do not remove insurance');
		}
		return true;

	}

	public function removeInsurance() {
	
		//Check if session insurance flag is set
		if (!$this->checkInsurance()) {
			return;
		}

		if (!$this->isAllOk()) {
			return; //Something wrong or sku not valid/set
		}
		$this->log('method remove insurance');
		$cart = $this->cart;
			
		//Checking if product is already in cart
		$result = $cart->getItems(); //To get also the not visible ones
		$itemsIds = array();
		$productToItem = array();
		if (count($result)>0) {
			foreach ($result as $cartItem) {
				$pid =  $cartItem->getProduct()->getId();
		    	array_push($itemsIds,$pid);
		    	$productToItem[$pid] = $cartItem;
			}
		}
		
		//var_dump($itemsIds, count($result)); die();
		if (in_array($this->productId, $itemsIds)) { 
			try {
				//$cart = $objectManager->create('Magento\Checkout\Model\Cart');
				$cart->removeItem($productToItem[$this->productId]->getId())->save();
			} catch (\Exception $e) {
				//Something wrong, probably the item is no longer there
				$this->log($e->getMessage(),true);
			}
			$this->removeFromSession(); //Removing also from session or the user will not be able to re-add
			$this->log('remove insurance from cart');
			return true;
		}

		return false;
	}
	public function removeInsurancecart($cartitemid) {
		$cart = $this->cart;
		//$cart->removeItem($cartitemid)->save();
		$this->removeFromSession(); //Removing also from session or the user will not be able to re-add
		return true;
		
	}
	public function addInsurance() {
		//Check if session insurance flag is set
		if (!$this->checkInsurance()) {
			return;
		}

		if (!$this->isAllOk()) {
			return; //Something wrong or sku not valid/set
		}

		$this->log('method add insurance');
		
		try {
		
			$cart = $this->cart;
			//Checking if product is already in cart
			$result = $cart->getItems(); //To get also the not visible ones
			$itemsIds = array();
			if (count($result)>0) {
				foreach ($result as $cartItem) {
					//Ignore product if it's a free Sampre
					if ($this->isProductFree($cartItem)) {
						$this->log('Free product in cart, ignore it');
					} else if ($this->isProductNoInsurance($cartItem)) {
						$this->log('Free product in cart, ignore it');
					} else {
			    		array_push($itemsIds, $cartItem->getProduct()->getId());
			    	}
				}
			}
			//var_dump($itemsIds, count($result)); die();
			if (in_array($this->productId, $itemsIds)) {
				return; //Already in cart, do nothing
			}
			if (count($itemsIds) == 0) {
				return; //Nothing to do, there are no items to add the insurance to
			}

			//If only item is the insurance then remove it
			if (count($itemsIds) == 1 && $this->isInsuranceInCart()) {
				$this->log('there is only one not free item in cart');
				return $this->removeInsurance();
			} else {
				
				$product = $this->product;
				$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
				$listBlock = $objectManager->get('\Magento\Catalog\Block\Product\ListProduct');	
				$product1 = $objectManager->create('Magento\Catalog\Model\Product')->load($this->productId);    
				$postParams = $listBlock->getAddToCartPostParams($product1);	
				$formKey = $objectManager->create('\Magento\Framework\Data\Form\FormKey')->getFormKey();
				$cart = $objectManager->create('Magento\Checkout\Model\Cart');
				$params = array();      
				$options = array();				
				$params['qty'] = 1;				
				$params['form_key'] = $formKey;
				$params['product'] = $this->productId;				
				$params['uenc'] =$postParams['data']['uenc'];				
				$params['options'] = $options;
				//var_dump($params); exit;
				$cart->addProduct($product, $params);
				$cart->save();
				//echo "we are herere"; exit;
				
			}
		} catch (\Exception $e) {
			//Something wrong, probably the item is no longer there
			$this->log($e->getMessage(),true);
			return false;
		}
		return true;
	}
	/*
	private function getPrice()
	{
		$product = $this->product;
	    $priceRender = $this->getLayout()->getBlock('product.price.render.default');
	    if (!$priceRender) {
	        $priceRender = $this->getLayout()->createBlock(
	            \Magento\Framework\Pricing\Render::class,
	            'product.price.render.default',
	            ['data' => ['price_render_handle' => 'catalog_product_prices']]
	        );
	    }

	    $price = '';
	    if ($priceRender) {
	        $price = $priceRender->render(
	            \Magento\Catalog\Pricing\Price\FinalPrice::PRICE_CODE,
	            $product,
	            [
	                'display_minimal_price'  => true,
	                'use_link_for_as_low_as' => true,
	                'zone' => \Magento\Framework\Pricing\Render::ZONE_ITEM_LIST
	            ]
	        );
	    }

	    return $price;
	}
	*/

	public function getProductData() {

		if (!$this->isAllOk()) {
			return false; //Something wrong or sku not valid/set
		}

		return array(
			'price' => '£'.number_format($this->product->getPrice(),2),
			'details' => $this->product->getShortDescription(),
			'ID' => $this->product->getId(),
		);

	}
	public function productExistById($productId)
    {
        if (!$this->productRepositoryInterface->getById($productId)) 
        {
            return false;
        }
		return true;
    }
}