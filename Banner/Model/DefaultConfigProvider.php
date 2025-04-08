<?php

namespace Yogesh\Banner\Model;

class DefaultConfigProvider {

    public function afterGetConfig(\Magento\Checkout\Model\DefaultConfigProvider $subject, array $output) {

        $abc = $output['totalsData']['items'];
        $value = array();

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        foreach ($abc as $data) {
			if(!empty($data['item_id'])){
            $id = $data['item_id'];
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $model = $objectManager->get('\Magento\Quote\Model\Quote\Item')->load($id);
            $p_id = $model->getProductId();
            $product = $objectManager->get('\Magento\Catalog\Model\Product')->load($p_id);
              
            $proOptions = json_decode($data['options']);
            $inveresedArray = array();
            $productOptionArray = array();
            
            if (!empty($proOptions)) {
                foreach ($proOptions as $option) {
                    $label = strtolower($option->label);
                    if( ( ( $label == 'drop' ) || ( $label == 'width' ) )
                            && ( !in_array( $product->getAttributeSetId(), array(10,11,12,13) ) ) )
					/*if(false)*/
                    {
                        $inveresedArray[$label] = $option->value;
                    }
					/// add code by yogesh///
					elseif( ( ( $label == 'drop' ) || ( $label == 'width' )) 
                        && ( in_array( $product->getAttributeSetId(), array(11) ) ) )
					{
						//$inveresedcurtainArray[$label] = $option['value'];
						if($label == 'width')
						{	
							$productOptionArray[] = array(
								'label' => 'Install Height',
								'value' => $option->value
							);
						}else{
							$productOptionArray[] = array(
								'label' => $key,
								'value' => $option->value
							);
						}
						
					}
                    else
                    {
                        $productOptionArray[] = array(
                            'label' =>  $label,
                            'value' =>  $option->value
                                );
                    }
                }
               
                /**if(!empty($inveresedArray) && is_array($inveresedArray))
                {
                    krsort($inveresedArray);
                    foreach($inveresedArray as $key => $iArray)
                    {
                        if($key == 'width')
                        {
                            $productOptionArray[] = array(
                                'label' => $key,
                                'value' => $inveresedArray['drop']
                                );
                        }
                        elseif($key == 'drop')
                        {
                            $productOptionArray[] = array(
                                'label' => $key,
                                'value' => $inveresedArray['width']
                                );
                        }
                    }
                }*/
				
                $data['options'] = json_encode($productOptionArray);
            }
            
            $productdescription = $product->getDescription();

            if (!empty($productdescription)) {
                $data['custom_description'] = $productdescription;
            }


            array_push($value, $data);
			}
        }
        $output['totalsData']['items'] = $value;
        // echo '<pre>';print_r($output['totalsData']['items']);exit;
        $output['quoteItemData'] = $output['totalsData']['items'];
        return $output;
    }

}
