<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Yogesh\Categorybanner\Block\Widget;

class CategoriesIcons extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface {
    
    protected $_promotionsFactory;
    protected $_resource;
    
    public function _construct()
    {
        parent::_construct();
        $this->setTemplate('widget/category_icons.phtml');
    }
    
    /**
     * 
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory
     * @param \Magento\Framework\App\ResourceConnection $resource
     */
    public function __construct(
    \Magento\Framework\View\Element\Template\Context $context, 
             \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory, 
            \Magento\Framework\App\ResourceConnection $resource
    ) {
        $this->_categoryCollectionFactory = $categoryCollectionFactory;
        $data = array();
        parent::__construct($context, $data);
    }
    
    function getCategoryLinks($category_ids = '')
    {
        if( !empty( $category_ids ) )
        {
            $collection =   $this->_categoryCollectionFactory->create();
            $collection->addAttributeToSelect('*');
            $collection->addIdFilter($category_ids);
            $collection->addIsActiveFilter();
            $collection->setOrder('category_sortorder', 'ASC');
            return $collection;
        }
        return false;
    }

    public function getImageMediaPath()
    {
        return $this->getUrl('pub/media', ['_secure' => $this->getRequest()->isSecure()]);
    }
}