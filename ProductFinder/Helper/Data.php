<?php

namespace Yogesh\ProductFinder\Helper;

use Exception;
use Magento\Eav\Model\Config;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Swatches\Helper\Data as SwatchData;
use Magento\Swatches\Helper\Media;
use Yogesh\ProductFinder\Model\ResourceModel\RoomCategory\CollectionFactory;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use Yogesh\ProductFinder\Model\ResourceModel\BlindCategory\CollectionFactory as BlindCategoryCollectionFactory;
use Yogesh\ProductFinder\Model\ResourceModel\MaterialCategory\CollectionFactory as MaterialCategoryCollectionFactory;
use Wyomind\ElasticsearchCore\Model\Formatter\BaseImage;
class Data extends AbstractHelper
{
    const XML_PATH_CUSTOMROUTE_ROUTE = 'catalog/product_finder/route';
    const XML_PATH_ALL_CATEGORY_ID = 'catalog/product_finder/category_id';
    const XML_PATH_FILTER_CATEGORY_IDS = 'catalog/category_use_in_filter/categories';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;
    private Config $eavConfig;
    private SwatchData $swatchHelper;
    private StoreManagerInterface $storeManager;
    private CollectionFactory $roomCollectionFactory;
    private CategoryCollectionFactory $categoryCollectionFactory;
    private BlindCategoryCollectionFactory $blindCategoryCollectionFactory;
    private MaterialCategoryCollectionFactory $materialCategoryCollectionFactory;
    private BaseImage $baseImage;
    private Media $swatchMedia;

    /**
     * Data constructor.
     *
     * @param Context $context
     * @param ScopeConfigInterface $scopeConfig
     * @param Config $eavConfig
     * @param SwatchData $swatchHelper
     * @param StoreManagerInterface $storeManager
     * @param CollectionFactory $roomCollectionFactory
     * @param CategoryCollectionFactory $categoryCollectionFactory
     * @param BlindCategoryCollectionFactory $blindCategoryCollectionFactory
     */
    public function __construct(
        Context              $context,
        ScopeConfigInterface $scopeConfig,
        Config               $eavConfig,
        SwatchData           $swatchHelper,
        Media $swatchMedia,
        StoreManagerInterface $storeManager,
        CollectionFactory $roomCollectionFactory,
        CategoryCollectionFactory $categoryCollectionFactory,
        BlindCategoryCollectionFactory $blindCategoryCollectionFactory,
        MaterialCategoryCollectionFactory $materialCategoryCollectionFactory,
        BaseImage $baseImage
    )
    {
        parent::__construct($context);
        $this->scopeConfig = $scopeConfig;
        $this->eavConfig = $eavConfig;
        $this->swatchHelper = $swatchHelper;
        $this->storeManager = $storeManager;
        $this->roomCollectionFactory = $roomCollectionFactory;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->blindCategoryCollectionFactory = $blindCategoryCollectionFactory;
        $this->materialCategoryCollectionFactory = $materialCategoryCollectionFactory;
        $this->baseImage = $baseImage;
        $this->swatchMedia = $swatchMedia;
    }

    /**
     * @return string
     */
    public function getModuleRoute()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_CUSTOMROUTE_ROUTE, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return int
     */
    public function getAllCategoryId()
    {
        return (int)$this->scopeConfig->getValue(self::XML_PATH_ALL_CATEGORY_ID, ScopeInterface::SCOPE_STORE);
    }

    public function getMaterialType()
    {
        $materialTypeList = [];
        try {
            $materialTypeCollection = $this->materialCategoryCollectionFactory->create();
            $mediaUrl =  $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
            foreach ($materialTypeCollection as $materialType) {
                $materialTypeList[$materialType->getId()] = [
                    'label' => $materialType->getData('material_category'),
                    'swatch_image' => $materialType->getData('material_icon') ?
                        $mediaUrl.'material_icon/icon'.$materialType->getData('material_icon') : ''
                ];
            }
        } catch (Exception $e) {
            $this->_logger->debug($e->getMessage());
        }
        return $materialTypeList;
    }

    public function getRoomType()
    {
        $roomTypeList = [];
        try {
            $roomTypeCollection = $this->roomCollectionFactory->create();
            $mediaUrl =  $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
            foreach ($roomTypeCollection as $roomType) {
                $roomTypeList[$roomType->getId()] = [
                    'label' => $roomType->getData('room_category'),
                    'swatch_image' => $roomType->getData('room_icon') ?
                        $mediaUrl.'room_type/icon'.$roomType->getData('room_icon') : ''
                ];
            }
        } catch (Exception $e) {
            $this->_logger->debug($e->getMessage());
        }
        return $roomTypeList;
    }

    public function getBlindType()
    {
        $blindTypeList = [];
        try {
            $blindTypeCollection = $this->blindCategoryCollectionFactory->create();
            $mediaUrl =  $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
            foreach ($blindTypeCollection as $blindType) {
                $blindTypeList[$blindType->getId()] = [
                    'label' => $blindType->getData('blind_category'),
                    'swatch_image' => $blindType->getData('blind_icon') ?
                        $mediaUrl.'blind_icon/icon'.$blindType->getData('blind_icon') : ''
                ];
            }
        } catch (Exception $e) {
            $this->_logger->debug($e->getMessage());
        }
        return $blindTypeList;
    }

    public function getColorList()
    {
        $colorList = [];
        try {
            $attribute = $this->eavConfig->getAttribute('catalog_product', 'umbrella_colour');
            $options = $attribute->getSource()->getAllOptions();
            $swatchMediaUrl = $this->swatchMedia->getSwatchMediaUrl();
            foreach ($options as $option) {
                if (isset($option['label']) && ($option['label'] != 'None') && ($optionValue = $option['label'])
                    && trim($optionValue)) {
                    $swatchInfo = $this->swatchHelper->getSwatchesByOptionsId([$option['value']]);
                    $swatchInfo = isset($swatchInfo[$option['value']])? $swatchInfo[$option['value']]: [];
                    $key = str_replace(' ', '_', $optionValue);
                    $key = str_replace('/', '', $key);
                    $imageName= isset($swatchInfo['value'])? $swatchInfo['value']: '';
                    $isSwatchImage = false;
                    if (str_contains($imageName, '.jpg') || str_contains($imageName, '.png')) {
                        $imageName = $swatchMediaUrl.$imageName;
                        $isSwatchImage = true;
                    }
                    $colorList[strtolower($key)] = [
                        'label' => $optionValue,
						'id'=>$option['value'],
                        'swatch_color' => $imageName,
                        'is_swatch_image' => $isSwatchImage
                    ];
                }
            }
        } catch (Exception $e) {
            $this->_logger->debug($e->getMessage());
        }
        return $colorList;
    }


    public function getFeatureOptions()
    {
        $featureList = [];
        try {
            $attribute = $this->eavConfig->getAttribute('catalog_product', 'shopfeature');
            $options = $attribute->getSource()->getAllOptions();
            foreach ($options as $option) {

                if (isset($option['label']) && ($option['label'] != 'None') && ($optionValue = $option['label'])
                    && trim($optionValue)) {
                    $featureList[$option['value']] = $optionValue;
                }
            }
        } catch (Exception $e) {
            $this->_logger->debug($e->getMessage());
        }
        return $featureList;
    }

    public function getCategoryFilter($currentCategoryId)
    {
        $categoryIds = $this->scopeConfig->getValue(self::XML_PATH_FILTER_CATEGORY_IDS, ScopeInterface::SCOPE_STORE);
        $categoryIds = explode(',', $categoryIds);
        $categoryForFilters = [];
        if (!empty($categoryIds)) {
            $categoryCollection = $this->categoryCollectionFactory->create()->addAttributeToSelect('*');
            if ($currentCategoryId) {
                $categoryCollection->addFieldToFilter('entity_id', ['neq' => $currentCategoryId]);
            }
            $categoryCollection->addFieldToFilter('entity_id', ['in' => $categoryIds]);
            foreach ($categoryCollection as $category) {
                $categoryForFilters[$category->getId()] = [
                    'name' => $category->getName(),
                    'url' => $category->getUrl()
                ];
            }
        }
        return $categoryForFilters;
    }

    public function getImageFromCache($imagePath)
    {
        return $this->baseImage->formatLargeImage($imagePath ,$this->storeManager->getStore());
    }
	
	public function getFinderProduct($data){
		//print_r($data['room_category']);
		
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		//$collection = $this->productCollectionFactory->create();
		//$collection->addAttributeToSelect('*');
		//$collection->addAttributeToFilter('type_id', ['eq' => 'simple']);
		$productIds =array();
		if(!empty($data['WD_field_productids'])){
			$WDproductIds =$data['WD_field_productids'];
		}else{
			$WDproductIds = '';
		}
		//$WDproductId = array();
		$blindCategory =array();
		$materialCategory =array();
		$umbrellaColour =array();
		$shopFeature =array();
		if(!empty($data['filter-drop']) && !empty($data['filter-width']) && empty($WDproductIds)){
			if($data['size'] == 'cm'){ $width = $data['filter-width']*10; $drop = $data['filter-drop']*10; }
			elseif($data['size'] == 'inch'){$width = round($data['filter-width']*25.4); $drop = round($data['filter-drop']*25.4);}
			else{$width = $data['filter-width']; $drop = $data['filter-drop'];}
			//$widthdropsql = "select distinct(s1.entity_id) from catalog_product_entity_varchar s1,catalog_product_entity_varchar s2 where ((s1.attribute_id=140 and s1.value >=".$width.") or (s1.attribute_id=142 and s1.value <=".$width.")) and ((s2.attribute_id=141 and s2.value >=".$drop.") or (s2.attribute_id=143 and s2.value <=".$drop.") )";
			$widthdropsql = "select distinct(s2.entity_id) from catalog_product_entity_varchar s1,catalog_product_entity_varchar s2 where ((s1.attribute_id=140 and s1.value <=".$width.") and (s2.attribute_id=142 and s2.value >=".$width.")) and s2.entity_id IN (select distinct(s4.entity_id) from catalog_product_entity_varchar s3,catalog_product_entity_varchar s4 where ((s3.attribute_id=141 and s3.value <=".$drop.") and (s4.attribute_id=143 and s4.value >=".$drop.")))";
			$connection = $objectManager->get('Magento\Framework\App\ResourceConnection')->getConnection('\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION'); 
			$widthdropproductArray = $connection->fetchAll($widthdropsql);
			foreach($widthdropproductArray as $product):
				$WDproductIds .= $product['entity_id'].',';
				//$WDproductId[] = $product['entity_id'];
			endforeach;
		}
		
		if( !empty($data['room_category']) || !empty($data['blind_category']) || !empty($data['material_category']) || !empty($data['umbrella_colour']) ||!empty($data['shopfeature'])){
		$sql ="SELECT DISTINCT entity_id FROM catalog_product_index_eav where";
		if(!empty($data['umbrella_colour'])){
			$umbrellacolour = implode(', ', $data['umbrella_colour']);			
				$sql .= ' value IN ('.$umbrellacolour.') and attribute_id=136';			
		}
		/*if(!empty($data['room_category'])){
			$roomcategory = implode(', ', $data['room_category']);
			$sql .= ' value IN ('.$roomcategory.') and attribute_id=500';
		}*/
		if(!empty($data['room_category'])){
			$roomcategory = implode(', ', $data['room_category']);
			if(empty($data['umbrella_colour'])){
				$sql .= ' value IN ('.$roomcategory.') and attribute_id=500';
			}else{
				$sql .= ' and entity_id IN (SELECT DISTINCT entity_id FROM catalog_product_index_eav where value IN ('.$roomcategory.') and attribute_id=500)';
			}
			//$sql .= ' value IN ('.$roomcategory.') and attribute_id=500';
		}
		if(!empty($data['blind_category'])){
			$blindcategory = implode(', ', $data['blind_category']);
			if(empty($data['room_category']) && empty($data['umbrella_colour'])){
				$sql .= ' value IN ('.$blindcategory.') and attribute_id=502';
			}
			else{
				$sql .= ' and entity_id IN (SELECT DISTINCT entity_id FROM catalog_product_index_eav where value IN ('.$blindcategory.') and attribute_id=502)';
			}
		}
		if(!empty($data['material_category'])){
			$materialcategory = implode(', ', $data['material_category']);
			if(empty($data['room_category']) && empty($data['blind_category']) && empty($data['umbrella_colour'])){
				$sql .= ' value IN ('.$materialcategory.') and attribute_id=503';
			}else{
			$sql .= ' and entity_id IN (SELECT DISTINCT entity_id FROM catalog_product_index_eav where value IN ('.$materialcategory.') and attribute_id=503)';
			}
		}
		/*if(!empty($data['umbrella_colour'])){
			$umbrellacolour = implode(', ', $data['umbrella_colour']);
			if(empty($data['room_category']) && empty($data['blind_category']) && empty($data['material_category']) ){
				$sql .= ' value IN ('.$umbrellacolour.') and attribute_id=136';
			}else{
			$sql .= ' and entity_id IN (SELECT DISTINCT entity_id FROM catalog_product_index_eav where value IN ('.$umbrellacolour.') and attribute_id=136';
			}
		}*/
		if(!empty($data['shopfeature'])){
			$shopfeature = implode(', ', $data['shopfeature']);
			if(empty($data['room_category']) && empty($data['blind_category']) && empty($data['material_category']) && empty($data['umbrella_colour'])){
				$sql .= ' value IN ('.$shopfeature.') and attribute_id=135';
			}else{
			$sql .= ' and entity_id IN (SELECT DISTINCT entity_id FROM catalog_product_index_eav where value IN ('.$shopfeature.') and attribute_id=135)';
			}
		}
		if(!empty($WDproductIds)){
			$sql .= ' and entity_id IN ('.trim($WDproductIds,',').')';
		}
		//echo $sql; exit;
		/*if(!empty($data['umbrella_colour']) && !empty($data['room_category'])){
			$sql .= ')';
		}
		if(!empty($data['umbrella_colour']) && !empty($data['blind_category']) && !empty($data['room_category'])){
			$sql .= ')';
		}
		if(!empty($data['umbrella_colour']) && !empty($data['material_category']) && (!empty($data['room_category']) || !empty($data['blind_category']))){
			$sql .= ')';
		}
		/*if(!empty($data['umbrella_colour']) && (!empty($data['room_category']) || !empty($data['blind_category']) || !empty($data['material_category']))){
			$sql .= ')';
		}
		if(!empty($data['shopfeature']) && (!empty($data['room_category']) || !empty($data['blind_category']) || !empty($data['material_category']) || !empty($data['umbrella_colour']))){
			$sql .= ')';
		}*/
		//echo $sql;
		$connection = $objectManager->get('Magento\Framework\App\ResourceConnection')->getConnection('\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION'); 
		$productArray = $connection->fetchAll($sql);
		foreach($productArray as $product):
			$productIds[] = $product['entity_id'];
			$product = $objectManager->get('Magento\Catalog\Model\Product')->load($product['entity_id']);
			$blindcategories = $product->getBlindCategory();
			$materialcategories = $product->getMaterialCategory();
			$umbrellacategories = $product->getUmbrellaColour();
			$shopfeaturs = $product->getShopfeature();
			$blindcategories = explode(',',$blindcategories ?? '');
			foreach($blindcategories as $blindcategor):
			if (!in_array($blindcategor, $blindCategory)){
				$blindCategory[] = $blindcategor;
			}
			endforeach;
			$materialcategories = explode(',',$materialcategories ?? '');
			foreach($materialcategories as $materialcategor):
			if (!in_array($materialcategor, $materialCategory)){
				$materialCategory[] = $materialcategor;
			}
			endforeach;
			$umbrellacategories = explode(',',$umbrellacategories ?? '');
			foreach($umbrellacategories as $umbrellacategor):
			if (!in_array($umbrellacategor, $umbrellaColour)){
				$umbrellaColour[] = $umbrellacategor;
			}
			endforeach;
			$shopfeaturs = explode(',',$shopfeaturs ?? '');
			foreach($shopfeaturs as $shopfeatur):
			if (!in_array($shopfeatur, $shopFeature)){
				$shopFeature[] = $shopfeatur;
			}
			endforeach;
		endforeach;
			/*$productcollection = $this->getProductSelection($productIds);
			//echo "<pre>"; print_r($productcollection-getData()
			$i=0;
			foreach ($productcollection as $product){
				echo "<pre>"; print_r($product->getData()); 
				 if($i==70):	exit;endif;
				if(!empty($product->getBlindCategory())): $blindCategory[] = $product->getBlindCategory(); endif;
				if(!empty($product->getMaterialCategory())): $materialCategory[] = $product->getMaterialCategory(); endif;
				//if(!empty($product['umbrella_colour'])): $umbrellaColour[] = $product['umbrella_colour']; endif;
				//if(!empty($product['shopfeature'])): $shopFeature[] = $product['shopfeature']; endif;
				$i++;
			}*/
		}else{
			$productArray = array();
		}
		
		
		$jsonData = ['result' => ['status' => 200, 'count' => count($productArray),'productids'=>$productIds,'blindcategory'=>$blindCategory,'materialcategory'=>$materialCategory,'umbrella_colour'=>$umbrellaColour,'shopfeature'=>$shopFeature,'WDproductID'=>$WDproductIds]];
		return $jsonData;
	}
	public function getProductList($data){
		$productids = explode(',',$data['productids']);
		//$productids = implode(', ', $productids);
		
		$paged = $data['paged'];
		
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		
		$productcollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection')		
        ->addAttributeToSelect('*')
		->setPageSize(20)
		->setCurPage($paged)
		->addAttributeToFilter('entity_id', ['in' => $productids]);
		
		
		return $productcollection;
	}
	public function getProductSelection($productIds){
		$productids = $productIds;
		//$productids = implode(', ', $productids);
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();		
		$productcollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection')		
        ->addAttributeToSelect(array( 'blind_category','umbrella_colour','shopfeature'))
		->setPageSize(100)
		->addAttributeToFilter('entity_id', ['in' => $productids]);
		return $productcollection;
	}
	
	
}