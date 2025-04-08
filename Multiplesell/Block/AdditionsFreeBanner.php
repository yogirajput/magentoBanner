<?php
namespace Yogesh\Multiplesell\Block;

class AdditionsFreeBanner extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{
	protected $_dataHelper;
	protected $formKey;
	protected $_registry;
    protected $_cartHelper;
    protected $imageBuilder;

    protected $_template = 'additionsfreebanner.phtml';

	public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Data\Form\FormKey $formKey,
		\Yogesh\Multiplesell\Helper\Data $dataHelper,     
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Block\Product\ImageBuilder $imageBuilder,
        array $data = []
	){
		parent::__construct($context, $data);		
		$this->_dataHelper = $dataHelper;
		$this->formKey = $formKey;
        $this->_registry = $registry;
        $this->imageBuilder = $imageBuilder;
	}

	public function isBannerEnabled() {
		return $this->_dataHelper->isAllOk();
	}

	public function getProducts() {
		return $this->_dataHelper->getFreeProducts();
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
    /**
     * Retrieve product image
     *
     * @param \Magento\Catalog\Model\Product $product
     * @param string $imageId
     * @param array $attributes
     * @return \Magento\Catalog\Block\Product\Image
     */
    public function getImage($product, $imageId, $attributes = [])
    {
        return $this->imageBuilder->setProduct($product)
            ->setImageId($imageId)
            ->setAttributes($attributes)
            ->create();
    }
}