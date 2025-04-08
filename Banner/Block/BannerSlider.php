<?php
namespace Yogesh\Banner\Block;

class Index extends \Magento\Framework\View\Element\Template 
{
	/**
     * @var \Yogesh\Banner\Model\bannerFactory
     */
    protected $_bannerFactory;
    protected $_store;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Yogesh\Banner\Model\BannerFactory $bannerFactory
//		\Magento\Store\Model\StoreManagerInterface $store
    ) {
		parent::__construct($context);
		$this->_bannerFactory = $bannerFactory;
		$this->_store = $context->getStoreManager();
	}
    
    public function getBannerImages(){
		$collection = $this->_bannerFactory->create()->getCollection();
		$collection->addFieldToFilter('is_active' ,1 );
		$collection->addFieldToFilter('group' ,1 );
    	$collection->setOrder('sort' ,'ASC');
		
    	return $collection;
    }
    public function getImageMediaPath(){
    	return $this->getUrl('pub/media',['_secure' => $this->getRequest()->isSecure()]);
    }
}