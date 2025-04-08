<?php
namespace Yogesh\Faq\Block;
use Magento\Store\Model\ScopeInterface;

class Faqgroup extends \Magento\Framework\View\Element\Template {

    protected $_faqgroupFactory;
	public function __construct(
			\Magento\Framework\View\Element\Template\Context $context,
			\Yogesh\Faq\Model\FaqgroupFactory $faqgroupFactory
		)
	{
		$this->_faqgroupFactory = $faqgroupFactory;
		$data = array();
		parent::__construct($context, $data);
	}
	
	public function getOptionArray(){
		$collection 			=	$this->_faqgroupFactory->create()->getCollection();	
		$array					=	array();
		foreach($collection->getData() as $data){
			$key				=	$data['title'];
			$value				=	$data['title'];
			$array[$key]		=	$value;
		}
		return $array;
	}
}