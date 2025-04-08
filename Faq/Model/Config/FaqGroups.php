<?php

namespace Yogesh\Faq\Model\Config;

class FaqGroups implements \Magento\Framework\Option\ArrayInterface
{

	 /**
     * @var \Yogesh\Faq\Model\faqFactory
     */
    protected $_faqFactory;
    
     /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Yogesh\Faq\Model\faqFactory $faqFactory
     * @param \Yogesh\Faq\Model\Status $status
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param array $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Yogesh\Faq\Model\faqFactory $faqFactory
    ) {
        $this->_faqFactory = $faqFactory;
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
    	$collection = $this->_faqFactory->create()->getCollection();
    	
    	foreach($collection as $faq){
    		$group[$faq->getGroup()]  = $faq->getGroup();
    	}
        return $group;
    }
}