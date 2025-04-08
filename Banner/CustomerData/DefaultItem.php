<?php
 namespace Yogesh\Banner\CustomerData;

class DefaultItem extends \Magento\Checkout\CustomerData\DefaultItem
{
    /**
     * {@inheritdoc}
     */
   protected function doGetItemData()
    {   
        //TODO: disable for virtual productsz
        
        $imageHelper = $this->imageHelper->init($this->getProductForThumbnail(), 'mini_cart_product_thumbnail');
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $product = $objectManager->create('Magento\Catalog\Model\Product')->load($this->item->getProductId());

        if ($product->getIsVirtual()) {
            return parent::doGetItemData();
        } else {
            //Go on, is a normal product
        }
       
        $Description     =   $product->getShortDescription();
        $productAttributeID     =   $product->getAttributeSetId();
        $options        =   $this->getOptionList();
        $newOptions = array();
        if(!empty($options) && is_array($options))
        {
            $inveresedArray =   array(); $inveresedcurtainArray =   array(); $newOptions = array();
            foreach($options as $option)
            { 
                $label = strtolower($option['label']);
                if( ( ( $label == 'drop' ) || ( $label == 'width' )) 
                        && ( !in_array( $product->getAttributeSetId(), array(10,11,12,13) ) ) )
                {
                    $inveresedArray[$label] = $option['value'];
                }
				/* added by yogesh */
				elseif( ( ( $label == 'drop' ) || ( $label == 'width' )) 
                        && ( in_array( $product->getAttributeSetId(), array(11) ) ) )
                {
                    //$inveresedcurtainArray[$label] = $option['value'];
					if($label == 'width')
					{	
						$newOptions[] = array(
							'label' => 'Install Height',
							'value' => $option['value']
						);
					}else{
						$newOptions[] = array(
							'label' => $key,
							'value' => $option['value']
						);
					}
                    
                }
				elseif( ( $label == 'install-height' ))
                {	
						$newOptions[] = array(
							'label' => 'Install Height',
							'value' => $option['value']
						);
					
                }				
				elseif(preg_match("/fine/i", $option['label'])) 
				{	
					$value = explode('|',$option['value'] ?? '');					
					$additionalvalue = $value[0];
                    $newOptions[] = array(
							'label' => $value[5],
							'value' => $additionalvalue
						);
                    
                }
				elseif( ( ( $label == 'blind-name' ) ) )
                {
                    $newOptions[] = array(
							'label' => 'Blind Name',
							'value' => $option['value']
						);
                    
                }
                else
                {
                    $newOptions[] = $option;
                }
            }
            if(!empty($inveresedArray))
            {
                krsort($inveresedArray);
                foreach($inveresedArray as $key => $iArray)
                {
                    if($key == 'width')
                    {	
						if($productAttributeID == 20){
							$newOptions[] = array(
								'label' => 'Number of Slats',
								'value' => $inveresedArray['drop']
							);
						}else{
							$newOptions[] = array(
								'label' => $key,
								'value' => $inveresedArray['drop']
							);
						}
                    }
                    elseif($key == 'drop')
                    {
                        $newOptions[] = array(
                            'label' => $key,
                            'value' => $inveresedArray['width']
                            );
                    }
                }
            }
        }
		
        return [
            'options' => $newOptions,
            'qty' => $this->item->getQty() * 1,
            'item_id' => $this->item->getId(),
            'configure_url' => $this->getConfigureUrl(),
            'is_visible_in_site_visibility' => $this->item->getProduct()->isVisibleInSiteVisibility(),
            'product_name' => $this->item->getProduct()->getName(),
			'product_id' => $this->item->getProduct()->getId(),
			'product_description' => $Description,
            'product_sku' => $this->item->getProduct()->getSku(),
            'product_url' => $this->getProductUrl(),
            'product_has_url' => $this->hasProductUrl(),
            'product_price' => $this->checkoutHelper->formatPrice($this->item->getCalculationPrice()),
            'product_price_value' => $this->item->getCalculationPrice(),
			'product_attribute_set_id' => $productAttributeID,
            'product_image' => [
                'src' => $imageHelper->getUrl(),
                'alt' => $imageHelper->getLabel(),
                'width' => $imageHelper->getWidth(),
                'height' => $imageHelper->getHeight(),
            ],
            'canApplyMsrp' => $this->msrpHelper->isShowBeforeOrderConfirm($this->item->getProduct())
                && $this->msrpHelper->isMinimalPriceLessMsrp($this->item->getProduct()),
        ];
    }
}
