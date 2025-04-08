<?php
namespace Yogesh\Faq\Block;
use Magento\Store\Model\ScopeInterface;

class Blinds extends \Magento\Framework\View\Element\Template {

    protected $_faqFactory;
	public function __construct(
						\Magento\Framework\View\Element\Template\Context $context,
						\Yogesh\Faq\Model\FaqFactory $faqFactory
					){
		$this->_faqFactory = $faqFactory;
		$data = array();
		parent::__construct($context, $data);
	}
	
	public function getCollectionData(){
		$collection = 	$this->_faqFactory->create()->getCollection();	
		$collection = 	$collection->addFieldToFilter('is_active', 1)
									->addFieldToFilter('groupname', 'Measures Blinds')
									->setOrder('sortorder', 'ASC');
		
		return $collection->getData();
	}	
}