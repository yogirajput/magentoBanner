<?php

namespace Yogesh\Banner\Model\Config;

class BannerGroups implements \Magento\Framework\Option\ArrayInterface
{

	 /**
     * @var \Yogesh\Banner\Model\bannerFactory
     */
    protected $_bannerFactory;
    
     /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Yogesh\Banner\Model\bannerFactory $bannerFactory
     * @param \Yogesh\Banner\Model\Status $status
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param array $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Yogesh\Banner\Model\BannerFactory $bannerFactory
    ) {
        $this->_bannerFactory = $bannerFactory;
    }
    
    
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
    	$optionArray = array();
    	foreach($this->toArray() as $arr){
    		$optionArray[] = array( 'value' => $arr , 'label' => $arr);
    	}
        return $optionArray;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
    	$group = array();
    	$collection = $this->_bannerFactory->create()->getCollection();
    	
    	foreach($collection as $banner){
    		$group[$banner->getBanner_id()]  = $banner->getBanner_id();
    	}
        return $group;
    }
}