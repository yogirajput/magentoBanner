<?php
namespace Yogesh\Categorybanner\Block\Widget;
 
class Catlisting implements \Magento\Framework\Option\ArrayInterface
{
    protected $_categoryCollectionFactory;
    protected $_resource;
    
    public function __construct(
    \Magento\Framework\View\Element\Template\Context $context, 
             \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory, 
            \Magento\Framework\App\ResourceConnection $resource
    ) {
        $this->_categoryCollectionFactory = $categoryCollectionFactory;
        $data = array();
    }
    
    public function toOptionArray()
    {
        $collection = $this->_categoryCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->addIsActiveFilter();
        $collection->addFieldToFilter('level',4);
       
        $categories     =    array();
        //$categories[]   =   'Select Category';
        if( count( $collection ) > 0 )
        { 
            foreach ($collection as $category) {
                $categories[]  =   array(
                    'label' =>  $category->getName(),
                    'value' =>  $category->getId()
                );
            }
        }
        return $categories;
    }
}