<?php
namespace Yogesh\Categorybanner\Model\Source;

class Banners implements \Magento\Framework\Option\ArrayInterface
{

    /**
     * Retrieve options array.
     *
     * @return array
     */
    public function toOptionArray()
    {
        $result = [];

        foreach (self::getOptionArray() as $index => $value) {
            if ('0' != $index) {
                $value .= ' ['.$index.']' ;
            }
            $result[] = ['value' => $index, 'label' => $value];
        }

        return $result;
    }

    /**
     * Retrieve option array
     *
     * @return string[]
     */
    public static function getOptionArray()
    {
        $object_manager = \Magento\Framework\App\ObjectManager::getInstance();
        $categoryRepository  = $object_manager->create('\Magento\Catalog\Model\CategoryRepository');
        $categoryFactory = $object_manager->create('\Magento\Catalog\Model\ResourceModel\Category\CollectionFactory');
        
        $store_manager = $object_manager->create('\Magento\Store\Model\StoreManagerInterface');
        $store = $store_manager->getStore();

        $categories = $categoryFactory->create();
        $categories->addAttributeToSelect('*');
  
        // select only active categories
        $categories->addIsActiveFilter();

        $values = array();
		$values[] = 'Select Overlay Category'; 
        foreach($categories as $category) {
        	if (
                $category->getCustomImage1() || $category->getCustomImage2() || $category->getCustomImage3() || $category->getCustomImage4()
                ||  $category->getCustomText1() || $category->getCustomText2() || $category->getCustomText3() || $category->getCustomText4()
                ) { 
                $values[$category->getId().''] = $category->getName(); 
            }
            $categoryObj = $categoryRepository->get($category->getId());
            $subcategories = $categoryObj->getChildrenCategories();
            foreach($subcategories as $subcategory) {
                if (
                    $subcategory->getCustomImage1() || $subcategory->getCustomImage2() || $subcategory->getCustomImage3() || $subcategory->getCustomImage4()
                    ||  $subcategory->getCustomText1() || $subcategory->getCustomText2() || $subcategory->getCustomText3() || $subcategory->getCustomText4()
                    ) { 
                    $values[$subcategory->getId().''] = $subcategory->getName(); 
                }
            }
        }

        return $values;
    }

    /**
     * Retrieve option array with empty value
     *
     * @return string[]
     */
    public function getAllOptions()
    {
        $result = [];

        foreach (self::getOptionArray() as $index => $value) {
            $result[] = ['value' => $index, 'label' => $value];
        }

        return $result;
    }

    /**
     * Retrieve option text by option value
     *
     * @param string $optionId
     * @return string
     */
    public function getOptionText($optionId)
    {
        $options = self::getOptionArray();

        return isset($options[$optionId]) ? $options[$optionId] : null;
    }

    public function setAttribute() {
        return $this;
    }
}
