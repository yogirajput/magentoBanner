<?php
namespace Yogesh\Insurance\Block;

class Insurancebanner extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{
	protected $_dataHelper;
	protected $formKey;
	protected $_registry;

    protected $_template = 'insurancebanner.phtml';

	public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Data\Form\FormKey $formKey,
		\Yogesh\Insurance\Helper\Data $dataHelper,     
        \Magento\Framework\Registry $registry,
        array $data = []
	){
		parent::__construct($context, $data);		
		$this->_dataHelper = $dataHelper;
		$this->formKey = $formKey;
        $this->_registry = $registry;
	}

	public function isBannerEnabled() {
		return $this->_dataHelper->isAllOk();
	}

	public function showBanner() {
		//Show banner if insurance is false
		return !$this->_dataHelper->checkInsurance();
	}
	public function showBannerCart() {
		//Show banner if insurance is false AND at least a valid product is in the cart
		return ($this->_dataHelper->validProductsInCart() && !$this->_dataHelper->checkInsurance());
	}

	public function getFormAction() {
		return $this->getUrl('appnova_insurance/index/session');
	}

	public function getProductData() {
		return $this->_dataHelper->getProductData();
	}
    
    public function getCurrentCategory()
    {        
        return $this->_registry->registry('current_category');
    }
    
    public function getCurrentProduct()
    {        
        return $this->_registry->registry('current_product');
    }   
 
    /**
     * get form key
     *
     * @return string
     */
    public function getFormKey()
    {
         return $this->formKey->getFormKey();
    }
}