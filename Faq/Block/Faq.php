<?php
namespace Yogesh\Faq\Block;
use Magento\Store\Model\ScopeInterface;

class Faq extends \Magento\Framework\View\Element\Template {

    protected $_faqFactory;
    public function __construct(
    \Magento\Framework\View\Element\Template\Context $context, \Yogesh\Faq\Model\FaqFactory $faqFactory
    ) {
        $this->_faqFactory = $faqFactory;
        $data = array();
        parent::__construct($context, $data);
    }

    public function getCollectionData() {
        $collection = $this->_faqFactory->create()->getCollection();
        $collection = $collection->addFieldToFilter('is_active', 1)
                ->addFieldToFilter('groupname', 'FAQ')
                ->setOrder('sortorder', 'ASC');

        return $collection->getData();
    }
    
    /**
     * Prepare global layout
     *
     * @return $this
     */
    protected function _prepareLayout() {
        $metaTitle = $this->_scopeConfig->getValue(
                'dotcom_category_settings/faq/faq_meta_title'
        );
        $metaKeyword = $this->_scopeConfig->getValue(
                'dotcom_category_settings/faq/faq_meta_keywords'
        );
        $metaDescription = $this->_scopeConfig->getValue(
                'dotcom_category_settings/faq/faq_meta_description'
        );
        $this->pageConfig->getTitle()->set($metaTitle);
        $this->pageConfig->setKeywords($metaKeyword);
        $this->pageConfig->setDescription($metaDescription);
        
        return parent::_prepareLayout();
    }
}
