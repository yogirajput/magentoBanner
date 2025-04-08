<?php

namespace Yogesh\Feature\Block;

use Magento\Store\Model\ScopeInterface;

class Feature extends \Magento\Framework\View\Element\Template
{

    protected $_featureFactory;
    protected $filterProvider;
    protected $eavConfig;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Yogesh\Feature\Model\FeatureFactory $_featureFactory,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        \Magento\Eav\Model\Config $eavConfig
    )
    {
        $this->_featureFactory = $_featureFactory;
        $this->_filterProvider = $filterProvider;
        $this->_eavConfig = $eavConfig;
        $data = array();
        parent::__construct($context, $data);
    }

     /**
     * Prepare global layout
     *
     * @return $this
     */
    protected function _prepareLayout() {
        $metaTitle = $this->_scopeConfig->getValue(
                'dotcom_category_settings/feature/feature_meta_title'
        );
        $metaKeyword = $this->_scopeConfig->getValue(
                'dotcom_category_settings/feature/feature_meta_keywords'
        );
        $metaDescription = $this->_scopeConfig->getValue(
                'dotcom_category_settings/feature/feature_meta_description'
        );
        $this->pageConfig->getTitle()->set($metaTitle);
        $this->pageConfig->setKeywords($metaKeyword);
        $this->pageConfig->setDescription($metaDescription);
        
        return parent::_prepareLayout();
    }

    public function getCollectionData()
    {
        $collection = $this->_featureFactory->create()->getCollection();
        $collection = $collection->addFieldToFilter('is_active', 1);

        return $collection;
    }

    public function getImageMediaPath()
    {
        return $this->getUrl('pub/media', ['_secure' => $this->getRequest()->isSecure()]);
    }

    public function getFilterImg($content)
    {

        $contentGenerating = $this->_filterProvider->getPageFilter()->filter($content);
        return $contentGenerating;

    }

    public function getTitle($id)
    {
        $attribute = $this->_eavConfig->getAttribute('catalog_product', 'shopfeature');
        $Values = $attribute->getSource()->getAllOptions();
        foreach ($Values as $data) {
            if ($id == $data['value']) {
                $options = $data['label'];

            }
        }
        return $options;
    }
}