<?php
namespace Yogesh\ProductFinder\Model\Source;

use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Framework\Option\ArrayInterface;

class Category implements ArrayInterface
{
    private CollectionFactory $collectionFactory;
    private CategoryFactory $categoryFactory;

    /**
     * @param CollectionFactory $collectionFactory
     * @param CategoryFactory $categoryFactory
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        CategoryFactory $categoryFactory
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->categoryFactory = $categoryFactory;
    }

    public function toOptionArray()
    {
        $optionArray = [];
        $categoryCollection = $this->collectionFactory->create()->addAttributeToSelect('*');
        foreach ($categoryCollection as $category) {
            $categoryName = $category->getName();
            if ($category->getParentId()) {
                $parentCategory = $this->categoryFactory->create()->load($category->getParentId());
                $categoryName = $parentCategory->getName().' - '.$categoryName;
            }

            $optionArray[] = [
                'value' => $category->getId(),
                'label' => $categoryName.'(Id - '.$category->getId().')',
            ];
        }
        return $optionArray;
    }
}