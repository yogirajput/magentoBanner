<?php

namespace Yogesh\Banner\Block\Widget;

class BannerSlider extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{
    /**
     * @var \Yogesh\Banner\Model\bannerFactory
     */
    protected $_bannerFactory;
    protected $_store;

    protected $_template = 'widget/bannerslider.phtml';

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Yogesh\Banner\Model\BannerFactory $bannerFactory
    )
    {
        parent::__construct($context);
        $this->_bannerFactory = $bannerFactory;
        $this->_store = $context->getStoreManager();
    }

    public function getBannerImages()
    {
        $collection = $this->_bannerFactory->create()->getCollection();
        $collection->addFieldToFilter('is_active', 1);
        $collection->addFieldToFilter('group', 0);
        $collection->setOrder('sort', 'ASC');

        return $collection;
    }

    public function getBlogImages()
    {
        $collection = $this->_bannerFactory->create()->getCollection();
        $collection->addFieldToFilter('is_active', 1);
        $collection->addFieldToFilter('group', 1);
        $collection->setOrder('sort', 'ASC');

        return $collection;
    }

    public function getImageMediaPath()
    {
	    // return $this->getUrl('pub/media', ['_secure' => $this->getRequest()->isSecure()]);
	    return  $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }
}
