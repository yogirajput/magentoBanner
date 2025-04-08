<?php

namespace Yogesh\Categorybanner\Block\Widget;
use Magento\Framework\Registry;
use Magento\Catalog\Api\CategoryRepositoryInterface;

class CategorybannerSlider extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{
    protected $_storeManager;
    protected $_registry;
    protected $_categoryRepository;
    protected $_category = false;
    protected $_categoryData = array();
    protected $_categoryColor = false;
    private $_productloader;
    private $levels = 3; //just to play safe with recursion

    protected $_template = 'widget/categorybannerslider.phtml';

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

    private function loadCategoryData() {

        $category = $this->_category;        
		
        $this->_categoryColor = false;

        $data = array();

        if (!$category || !is_object($category)) {
            $this->_categoryData = $data;
            return;
        }

        $custom_image_1 = $category->getCustomImage1();
        $custom_image_2 = $category->getCustomImage2();
        $custom_image_3 = $category->getCustomImage3();
        $custom_image_4 = $category->getCustomImage4();
        $custom_text_1  = $category->getCustomText1();
        $custom_text_2  = $category->getCustomText2();
        $custom_text_3  = $category->getCustomText3();
        $custom_text_4  = $category->getCustomText4();
        $custom_link_1  = $category->getCustomLink1();
        $custom_link_2  = $category->getCustomLink2();
        $custom_link_3  = $category->getCustomLink3();
        $custom_link_4  = $category->getCustomLink4();

        $color = trim((string)$category->getCustomBackgroundColor());
        if ($color && '' != $color) {
            $this->_categoryColor = $color;
        }

        if ($custom_image_1 || $custom_text_1) {
            $data[] = array('i' => $custom_image_1, 't' => $custom_text_1, 'l' => $custom_link_1);
        }
        if ($custom_image_2 || $custom_text_2) {
            $data[] = array('i' => $custom_image_2, 't' => $custom_text_2, 'l' => $custom_link_2);
        }
        if ($custom_image_3 || $custom_text_3) {
            $data[] = array('i' => $custom_image_3, 't' => $custom_text_3, 'l' => $custom_link_3);
        }
        if ($custom_image_4 || $custom_text_4) {
            $data[] = array('i' => $custom_image_4, 't' => $custom_text_4, 'l' => $custom_link_4);
        }
		//var_dump( $data);
        $this->_categoryData = $data;
    }

    private function getValidCategory() {

        if (!$this->_category) {

            //Check for product
            $product = $this->_registry->registry('product');

            if ($product) {

                    $product = $this->_productLoader->create()->load($product->getId());
                    $categoryId = $product->getData('custom_category_banner_id');
					
					//yogesh comments//
                   if (!$categoryId && $categoryId == 0) {

                        //Go to default
                        //$categoryId = $this->_storeManager->getStore()->getRootCategoryId();
						$categoryId = $product->getCategoryIds();
					//print_r($categoryIds);

                    }

            } else {

                $category = $this->_registry->registry('current_category');

                if (!$category) {

                    //Go to default
                    $categoryId = $this->_storeManager->getStore()->getRootCategoryId();

                } else {

                    $categoryId = $category->getId();

                }
            }

            if (!$categoryId) {

                $this->_category = -1;
                $this->_categoryData = array();

            } else {
                if(is_array($categoryId)){
					$categorydata = array();					
					foreach($categoryId as $category): 
					 $catdata = $this->_categoryRepository->get($category, $this->_storeManager->getStore()->getId());					 
					endforeach;
					$this->_category = $categorydata;
					
				}else{
                $category = $this->_categoryRepository->get($categoryId, $this->_storeManager->getStore()->getId());
                $this->_category = $category;
				}

            }

        }

    }

    public function getCategorybanners()
    {
		//code commnes by yogesh//
       // if (!$this->_category) {
           // $this->getValidCategory();

           // if (!is_object($this->_category) && $this->_category == -1) {
               // return $this->_categoryData; //Nothing to search, it's not a product with a category or a category page
           // }
       // }

        //$this->loadCategoryData();

        /*if ($this->_category && count($this->_categoryData) == 0 && $this->_category->getId() != $this->_storeManager->getStore()->getRootCategoryId()) {
            //Nothing set, look for parent

            $parent = $this->_category->getParentCategory();

            if ($parent && $this->levels>0) {
                $this->_category = $parent;
                $this->levels--;
                $this->getCategorybanners();
            }
        }*/
		
		
		$product = $this->_registry->registry('product');

            if ($product) {

                    $product = $this->_productLoader->create()->load($product->getId());
                    $categoryId = $product->getData('custom_category_banner_id');
					
					//yogesh code//
					//get all category if ategory banner is not selected//
                   if (!$categoryId && $categoryId == 0) {
						$categoryId = $product->getCategoryIds();
                    }

            }
			 $data = array();
			if(is_array($categoryId)){
				$categorydata = array();					
				foreach($categoryId as $category): 
				 $category = $this->_categoryRepository->get($category);	
					//var_dump($category->getData());
					$custom_image_1 = $category->getCustomImage1();
					$custom_image_2 = $category->getCustomImage2();
					$custom_image_3 = $category->getCustomImage3();
					$custom_image_4 = $category->getCustomImage4();
					$custom_text_1  = $category->getCustomText1();
					$custom_text_2  = $category->getCustomText2();
					$custom_text_3  = $category->getCustomText3();
					$custom_text_4  = $category->getCustomText4();
					$custom_link_1  = $category->getCustomLink1();
					$custom_link_2  = $category->getCustomLink2();
					$custom_link_3  = $category->getCustomLink3();
					$custom_link_4  = $category->getCustomLink4();

					$color = trim((string)$category->getCustomBackgroundColor());
					if ($color && '' != $color) {
						$this->_categoryColor = $color;
					}

					if ($custom_image_1 || $custom_text_1) {
						$data[] = array('i' => $custom_image_1, 't' => $custom_text_1, 'l' => $custom_link_1);
					}
					if ($custom_image_2 || $custom_text_2) {
						$data[] = array('i' => $custom_image_2, 't' => $custom_text_2, 'l' => $custom_link_2);
					}
					if ($custom_image_3 || $custom_text_3) {
						$data[] = array('i' => $custom_image_3, 't' => $custom_text_3, 'l' => $custom_link_3);
					}
					if ($custom_image_4 || $custom_text_4) {
						$data[] = array('i' => $custom_image_4, 't' => $custom_text_4, 'l' => $custom_link_4);
					}
				endforeach;
				//die;
				$this->_categoryData = $data;
				
			}else{
				$category = $this->_categoryRepository->get($categoryId, $this->_storeManager->getStore()->getId());
				$custom_image_1 = $category->getCustomImage1();
				$custom_image_2 = $category->getCustomImage2();
				$custom_image_3 = $category->getCustomImage3();
				$custom_image_4 = $category->getCustomImage4();
				$custom_text_1  = $category->getCustomText1();
				$custom_text_2  = $category->getCustomText2();
				$custom_text_3  = $category->getCustomText3();
				$custom_text_4  = $category->getCustomText4();
				$custom_link_1  = $category->getCustomLink1();
				$custom_link_2  = $category->getCustomLink2();
				$custom_link_3  = $category->getCustomLink3();
				$custom_link_4  = $category->getCustomLink4();

				$color = trim((string)$category->getCustomBackgroundColor());
				if ($color && '' != $color) {
					$this->_categoryColor = $color;
				}

				if ($custom_image_1 || $custom_text_1) {
					$data[] = array('i' => $custom_image_1, 't' => $custom_text_1, 'l' => $custom_link_1);
				}
				if ($custom_image_2 || $custom_text_2) {
					$data[] = array('i' => $custom_image_2, 't' => $custom_text_2, 'l' => $custom_link_2);
				}
				if ($custom_image_3 || $custom_text_3) {
					$data[] = array('i' => $custom_image_3, 't' => $custom_text_3, 'l' => $custom_link_3);
				}
				if ($custom_image_4 || $custom_text_4) {
					$data[] = array('i' => $custom_image_4, 't' => $custom_text_4, 'l' => $custom_link_4);
				}
				$this->_categoryData = $data;
			}
		
        return $this->_categoryData;
    }
	
	public function getProductbanners()
    {
		$data = array();
        $product = $this->_registry->registry('product');
		if($product){
        $product = $this->_productLoader->create()->load($product->getId());
		$producttext = $product->getData('appnova_product_text_overlay');
		$productlink = $product->getData('appnova_product_banner_link_overlay');
		if ($producttext || $productlink) {
            $data[] = array('i' => $producttext, 't' => $productlink);
        }
		return $data;
		}
		else{
			return $data;
		}
    }
	
	public function isCategorybannersenable()
    {

        $product = $this->_registry->registry('product');
		if($product){
        $product = $this->_productLoader->create()->load($product->getId());
                    
        return $product->getData('appnova_overlay');}
		else{
			return false;
		}
    }
	
	public function isproductbannersenable()
    {

        $product = $this->_registry->registry('product');
        $product = $this->_productLoader->create()->load($product->getId());
                    
        return $product->getData('appnova_product_overlay');
    }

    public function getCategorybannersColor() {

        if (!$this->_category) {
            $this->getValidCategory();
        }

        $this->loadCategoryData();

        return $this->_categoryColor;

    }

    public function getImageMediaPath()
    {
        return $this->getUrl('pub', ['_secure' => $this->getRequest()->isSecure()]);
    }
}