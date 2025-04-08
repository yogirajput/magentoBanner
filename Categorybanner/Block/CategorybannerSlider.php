<?php
namespace Yogesh\Categorybanner\Block;

class Index extends \Magento\Framework\View\Element\Template 
{
    protected $_store;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context
    ) {
		parent::__construct($context);
		$this->_store = $context->getStoreManager();
	}
}