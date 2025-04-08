<?php

namespace Yogesh\CategoryFeatures\Block\Widget;
use Magento\Framework\Registry;
use Magento\Catalog\Api\CategoryRepositoryInterface;

class FeaturesBlock extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{
    protected $_storeManager;
    protected $_registry;
    protected $_categoryRepository;
    private $_productloader;

    protected $_template = 'widget/priceblock.phtml';

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        Registry $registry,
        \Magento\Catalog\Model\CategoryRepository $categoryRepository,
        \Magento\Catalog\Model\ProductFactory $productLoader
    )
    {
        parent::__construct($context);
        $this->_storeManager = $context->getStoreManager();
        $this->_registry = $registry;
        $this->_categoryRepository = $categoryRepository;
        $this->_productLoader = $productLoader;
    }

    public function getProduct() {
        return  $this->_registry->registry('product');
    }

   public function getFeatures($product = false) {

        if (!$product) {
            //Check for product
            $product = $this->_registry->registry('product');
        }

        if (!is_object($product)) {
            $id = (int)$product;
        } else {
            $id = $product->getId();
        }

        //Load product by product id
        $product = $this->_productLoader->create()->load($product->getId());

        if (!$product) {
            return false;
        }

        //Get the product categories
        $coll = clone $product->getCategoryCollection();
		
        $max = 12;
        for ($i = 1; $i<=$max; $i++) {
            $coll->addAttributeToSelect('appnova_cat_a'.$i.'_title');
            $coll->addAttributeToSelect('appnova_cat_a'.$i.'_body');
        }

        /*
        $mainCatId = 3;

        
        $_category = $coll->addFieldToFilter('path', array('like' => '%/'.$mainCatId.'/%'))->setOrder('level', 'DESC')->setOrder('parent_id', 'ASC')->setOrder('position', 'ASC')->getFirstItem();

        if ($_category && $_category->getEntityId()>0) {
          //Found in Shop blinds children
        } else {
          //Check if it IS shopblinds
          $coll = clone $_product->getCategoryCollection();
          $_category = $coll->addFieldToFilter('entity_id', array('eq' => $mainCatId))->getFirstItem();
          if ($_category && $_category->getEntityId()>0) {
              //Ok it IS the main cat
          } else {
              //Normal rules
              $coll = clone $_product->getCategoryCollection();
              $_category = $coll->setOrder('level', 'DESC')->setOrder('parent_id', 'ASC')->setOrder('position', 'ASC')->getFirstItem();
          } 
        }        

        $category = $_category->load($_category->getEntityId());
        */
		$prodfeatures = array();
        $max = 12;
        for ($i = 1; $i<=$max; $i++) {
            $title = $product->getDataByKey('appnova_prod_a'.$i.'_title');
            $body = $product->getDataByKey('appnova_prod_a'.$i.'_body');
            if ($title && $body) {
                $prodfeatures[strtolower($title)] = array('title' => $title, 'body' => $body);
            }
        }
		
        $max = 12;

        //Check if category has features

        $features = array();
        
        foreach ($coll as $category) {
            //$category = $_category->load($_category->getEntityId());
            //$category = $this->_categoryRepository->get($_category->getEntityId(), $this->_storeManager->getStore()->getId());
            for ($i = 1; $i<=$max; $i++) {
                //$methodName = 'getAppnovaCatA'.$i.'Title';
                $title = $category->getDataByKey('appnova_cat_a'.$i.'_title');
                $body = $category->getDataByKey('appnova_cat_a'.$i.'_body');
                if ($title && $body) {
					if(in_array(strtolower($title),$prodfeatures)){ continue;}
					else{
                    $features[strtolower($title)] = array('title' => $title, 'body' => $body);
					}
                }
            }
        }       

        //Get the product features
        // will override categories'
        
			$features = array_merge($prodfeatures,$features);
        if (count($features) == 0) {
            $features = false;
        }

        return $features;
   }

}