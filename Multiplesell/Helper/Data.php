<?php

namespace Yogesh\Multiplesell\Helper;

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
	private $productsSkus = false;
	private $productsFreeSkus = false;
	private $productsIds = [];
	private $products = [];
	private $freeproducts = false;
	private $logger = true;
	private $enableLog = false;
	private $messageManager;
	private $allOrNothing = false;
	private $insurance = false;

	public function __construct(
		Context $context, 
		Cart $cart, 
		ProductFactory $productFactory,
		ProductRepositoryInterface $productRepositoryInterface,
        Session $checkoutSession,
        \Magento\Framework\Message\ManagerInterface $messageManager
	){
		parent::__construct($context);
		$this->productFactory = $productFactory;
		$this->productRepositoryInterface = $productRepositoryInterface;
		$this->checkoutSession = $checkoutSession;	
		$this->cart = $cart;	
		$this->logger = $context->getLogger();
		$this->messageManager = $messageManager;
		$this->productsSkus = array();
		$this->productsFreeSkus = array();
		$this->getInsuranceProduct();
	}

	private function getInsuranceProduct() {
		$product_sku = $this->scopeConfig->getValue('dotcom_category_settings/insurance_settings/product_sku', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
		if (!$product_sku) {
			return false;
		}
		
		$this->insurance = $product_sku;
	}

	public function enableLog() {
		$this->enableLog = true;
	}

	public function disableLog() {
		$this->enableLog = false;
	}

	public function log($message = '', $error = false) {
		if (!$this->enableLog) {
			return;
		}
		if ($error) {
			$message = '[ERROR] : '.$message;
		} 
		$message = '[CROSSSELL] '.$message;
		$this->logger->info($message);
	}

	public function unsetCrosssellOption($item = false) {
		$options = array();
		if (!$item) {
			return $options;
		}
		$update = false;
		$customOptions = $item->getProduct()->getTypeInstance(true)->getOrderOptions($item->getProduct());
		if ($customOptions && count($customOptions)>0) {	
			foreach ($customOptions as $k => $o) {
				if (is_array($o)) {
					foreach ($o as $key => $value) {
						if ($key == 'crosssell') {
							unset($customOptions[$k][$key]);
							$update = true;
						}
					}
				} else {
					if ($k == 'crosssell') {
						unset($customOptions[$k]);
						$update = true;
					}
				}       
			}
		}
		if ($update) {
			$this->cart->updateItem($item->getId(), $customOptions);
		}
		return $customOptions;
	}


	private function getOptions($item = false) {
		$options = array();
		if (!$item) {
			return $options;
		}
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
		return $options;
	}

	public function isProductFree($item = false) {
		if (!$item) {
			return false;
		}
		$options = $this->getOptions($item);  
		//echo "<pre>"; print_r($options);
		/*commnet bt yogesh */
		//return isset($options['Free Sample']) || in_array('Free Sample', $options);
		/* add new code by yogesh*/
		if(isset($options['Request'])): 
			return  isset($options['Free Sample']) || in_array('Free Sample', $options);
		else:
			return false;
		endif;
	}

	public function isFromCrosssell($item = false) {
		$this->log('Check');
		if (!$item) {
			return false;
		}
		if ($this->isProductFree($item)) {
			return false;
		}
		$options = $this->getOptions($item);
		return isset($options['crosssell']) || in_array('crosssell', $options);
	}

	public function explodeSkus($skus = '') {
		$newskus = array();
		if(!empty($skus)){
		$rows = explode(PHP_EOL, $skus);
        //$rows = explode(',', $skus ?? '');	
	 	if (count($rows) == 0) {
	 		return $newskus;
	 	}
	 	foreach ($rows as $row) {
	 	  $values = explode("|", $row);
	 	  if (count($values)<2) {
	 	  	//something wrong, skip
	 	  	continue;
	 	  }
	 	  foreach ($values as $key => $value) {
	 	  	$values[$key] = trim($value);
	 	  }
	 	  $newskus[] = $values[0];
	 	}
		}
	 	return $newskus;
	}

	public function qtyFromSku($skus = '', $sku = '') {
		
		$this->log('Checking: '.$skus.' with '.$sku);
		if (!$skus || !$sku || '' == trim($sku) || '' ==trim($skus)) {
			return 0;
		}
		$rows = explode(PHP_EOL, $skus);
	 	if (count($rows) == 0) {
	 		return 0;
	 	}
	 	$qty = 0;
		
	 	foreach ($rows as $row) {
	 	  $values = explode("|", $row);
	 	  if (count($values)<2) {
	 	  	//something wrong skip
	 	  	continue;
	 	  }
	 	  foreach ($values as $key => $value) {
	 	  	$values[$key] = trim($value);
	 	  }
	 	  $this->log(print_r($values, true));
	 	  if ($values[0] == $sku) {
	 	  	$qty += (int)$values[1];
	 	  }
	 	}
	 	return $qty;
	}

	public function areAllFree() {
		$this->log('check all free');

		//Getting all products in cart
		$cart = $this->cart;
		$result = $cart->getItems();
		foreach ($result as $item) {
			if ($this->isProductFree($item)) {
				$this->log('Skip free item');
				continue;
			}
			if ($this->isProductFreeOnly($item)) {
				$this->log('Skip free only item');
				continue;
			}
			return false; //At least one is not free
		}
		return true;
	}

	public function isProductFreeOnly($item = false) {
		if (!$item) {
			return false;
		}
		$product = $item->getProduct();
		if ($product && in_array($product->getSku(), $this->productsFreeSkus)) {
			return true;
		}
		return false;
	}

	public function calculateQuantity($item = false) {
		$this->log('Calculate');
		if (!$item) {
			return 0;
		}
		if (!$this->isFromCrosssell($item)) {
			return 0;
		}		
		//Getting all products in cart
		$cart = $this->cart;
		$result = $cart->getItems();
		$toBeAdded = 0;
		$alreadyAdded = 0;
		$currProductId = $item->getProduct()->getId();
		foreach ($result as $cartItem) {		
			if ($cartItem->getId() == $item->getId()) {
				$this->log('Skip same item');
				//Ignore the same item
				continue;
			}
			$product = $cartItem->getProduct();
			/* commented by YOgesh */
			/*if ($this->isProductFree($item)) { 
				$this->log('Skip free item');
				continue;
			}*/
			// ad below code by yogesh//
			$customOptions = $cartItem->getProduct()->getTypeInstance(true)->getOrderOptions($cartItem->getProduct());
			if (isset($customOptions['info_buyRequest']['Request']) && $customOptions['info_buyRequest']['Request'] =='Free Sample' ) { 
				$this->log('Skip free item');
				continue;
			}
			//////
			if ($product->getId() == $currProductId) {
				$this->log('Skip same product');
				$alreadyAdded += $item->getQty();
				continue;
			}
				
			$skus = $product->getAppnovaMultipleXsell();
			$toBeAdded += $this->qtyFromSku($skus, $item->getProduct()->getSku())*$cartItem->getQty();
			
		}
		$toBeAdded = $toBeAdded - $alreadyAdded;
		if ($toBeAdded < 0) {
			$toBeAdded = 0;
		}
		if ($toBeAdded > 0) {
	 		//$this->messageManager->addSuccessMessage('Quantity of sku:'.$item->getProduct()->getSku().' set automatically to '.$toBeAdded.' you can reduce it from the cart');
		}
		return $toBeAdded;
	}

	//Custom block methods

	public function areSkusDefined() {
		$productsSkus = $this->scopeConfig->getValue('dotcom_category_settings/crosssell_settings/products_sku', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
		if (!$productsSkus) {
			return false;
		}
		
		$this->productsSkus = explode(PHP_EOL, $productsSkus);
		
		foreach ($this->productsSkus as $key => $value) {
			$this->productsSkus[$key] = trim($value);
		}
		
		foreach ($this->productsSkus as $key => $value) {
			if ($value== '') {
				unset($this->productsSkus[$key]);
			}
		}

		$this->log('config skus');
		$this->log(print_r($this->productsSkus,true));

		$this->getAllOrNothing();
		
		return count($this->productsSkus) + count($this->allOrNothing) > 0;
	}

	public function areFreeSkusDefined() {
		$productsFreeSkus = $this->scopeConfig->getValue('dotcom_category_settings/crosssell_settings/free_only_products_sku', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
		if (!$productsFreeSkus) {
			return false;
		}
		
		$this->productsFreeSkus = explode(PHP_EOL, $productsFreeSkus);
		foreach ($this->productsFreeSkus as $key => $value) {
			$this->productsFreeSkus[$key] = trim($value);
		}
		foreach ($this->productsFreeSkus as $key => $value) {
			if ($value== '') {
				unset($this->productsFreeSkus[$key]);
			}
		}

		$this->log('free skus');
		$this->log(print_r($this->productsFreeSkus,true));

		return count($this->productsFreeSkus) > 0;
	}	

	public function areSkusValid() {

		//TODO: Try/catch as if product SKU is not valid an exception is fired
		foreach ($this->productsSkus as $key => $value) {
			if ($value== '') {
				unset($this->productsSkus[$key]);
			}
		}
		
		foreach ($this->productsSkus as $key => $value) { 
			try {
				$product = $this->productRepositoryInterface->get($value);				
				$this->products[$value] = $product;
				$this->productsIds[$value] = $product->getId();
			} catch (\exception $e) { //echo $e->getmessage(); echo "check".$value.'#####<br/>';
				//unset the key
				unset($this->productsSkus[$key]);
			}
		}
		//var_dump($this->productsSkus);
		foreach ($this->allOrNothing as $key => $value) {
			try {
				$product = $this->productRepositoryInterface->get($value);
				$this->products[$value] = $product;
				$this->productsIds[$value] = $product->getId();
			} catch (\exception $e) {
				//unset the key
				unset($this->allOrNothing[$key]);
			}
		}
		
		return count($this->productsIds) + count($this->allOrNothing) > 0;

	}

	public function areFreeSkusValid() {
		
		if (!is_array($this->freeproducts)) {
			$this->freeproducts = array();
		}
		foreach ($this->productsFreeSkus as $key => $value) {
			try {
				$product = $this->productRepositoryInterface->get($value);
				if ($product && !$this->isInCart($product)) {
					$this->freeproducts[$value] = $product;
				}
			} catch (\exception $e) {
				//unset the key
				unset($this->productsFreeSkus[$key]);
			}
		}

		return count($this->productsFreeSkus) > 0;

	}	

	private function isInCart($product = false) {
		if (!$product) {
			return false;
		}
		$cart = $this->cart;
		$result = $cart->getItems();
		foreach ($result as $cartItem) {
			$p = $cartItem->getProduct();
			if ($p->getId() == $product->getId()) {
				return true;
			}
		}
		return false;
	}

	public function isAllOk() {
	
		if (!$this->areSkusDefined()) {
			return false;
		}

		if (!$this->areSkusValid()) {
			return false;
		}

		return true;		
	}

	public function isAllFreeOk() {

		if (!$this->areFreeSkusDefined()) {
			return false;
		}

		if (!$this->areFreeSkusValid()) {
			return false;
		}

		return true;		
	}	

	public function getProducts() {
		//Return a list of products based on the SKUS in the settings
		if ($this->isAllOk()) {		
			$this->crossCheckSkus();
      //Remove if already in cart
      if ($this->products && count($this->products)>0) {
	      foreach ($this->products as $key => $value) {
	            if ($this->isInCart($value)) {
	                unset($this->products[$key]);
	            }
	      }
	    }
			return $this->products;		
		}
		return array();
	}
	public function getFreeProducts() {
		//Return a list of products based on the SKUS in the settings
		if ($this->isAllFreeOk()) {			
			if ($this->areAllFree()) {
        //Remove if already in cart
        if ($this->products && count($this->products)>0) {
          foreach ($this->products as $key => $value) {
            if ($this->isInCart($value)) {
              unset($this->products[$key]);
            }
          }
        }
				return $this->freeproducts;
			} 
		}
		return array();
	}

	public function crossCheckSkus() {

		//Gets the additional skus in the cart, removes from the arrays all products that cannot be added to current products

		//Getting all products in cart
		$cart = $this->cart;
		$result = $cart->getItems();
		$cartskus = array();
		$validItems = array();
		$this->log('productsSkus');
		//Adding ALL or Nothing products to the products skus		
		if ($this->allOrNothing) {
			$this->productsSkus = array_merge($this->productsSkus, $this->allOrNothing);
		}
		$this->productsSkus = array_unique($this->productsSkus);
		
		$this->log(print_r($this->productsSkus,true));
		foreach ($result as $cartItem) {
			$product = $cartItem->getProduct();
			if ($this->productsIds && in_array($product->getId(), $this->productsIds)) {
				$this->log('Skip to be added item');
				//Ignore the to be added items
				continue;
			}
			//Ignore free items	
			if ($this->isProductFree($cartItem)) {
				$this->log('Skip free item');
				continue;
			}
			$validItems[] = $cartItem;
			$skus = $product->getAppnovaMultipleXsell();			
			$cartskus = array_merge($cartskus, $this->explodeSkus($skus));
		}
		
		//If a sku is not in the cartSkus then remove it
		foreach ($this->productsSkus as $key => $value) {
			if (!in_array($value, $cartskus)) {
				unset($this->productsSkus[$key]);
				unset($this->productsIds[$value]);
				unset($this->products[$value]);
			}
		}
		//Remove SKUS that are "all or nothing"
		foreach ($this->productsSkus as $key => $value) {
			if ($this->allOrNothing && in_array($value, $this->allOrNothing)) {
				//The SKU needs to be on all valid products different from himself
				$remove = false;
				foreach ($validItems as $cartItem) {
					$product = $cartItem->getProduct();
					if ($product->getSku() == $value) {
						//skip the same product
						continue;
					} else if ($this->insurance && $product->getSku() == $this->insurance) {
						//skip insurance
						continue;
					} else {
						$skus = $product->getAppnovaMultipleXsell();						
						$skus = $this->explodeSkus($skus);
						if (in_array($value, $skus)) {
							//All ok
						} else {
							//At least one product is not valid
							$remove = true;
						}	
					}
				}
				if ($remove) {
					unset($this->productsSkus[$key]);
					unset($this->productsIds[$value]);
					unset($this->products[$value]);
				}
			}
		}
		$this->log('crosssell');
		$this->log(print_r($this->productsSkus,true));
	}

	private function getAllOrNothing() {
		$allOrNothing = ''.$this->scopeConfig->getValue('dotcom_category_settings/crosssell_settings/all_or_nothing_products_sku', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
		$this->allOrNothing = explode(PHP_EOL, $allOrNothing);
		$this->log('allOrNothing');
		$this->log(print_r($this->allOrNothing,true));
		return count($this->allOrNothing);
	}

}
