<?php

namespace Yogesh\ProductFinder\Model\Product\MaterialCategory;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Yogesh\ProductFinder\Model\ResourceModel\MaterialCategory\CollectionFactory;

class Option extends AbstractSource
{
    protected $optionFactory;

    /**
     * @var CollectionFactory
     */
    private $materialCollectionFactory;

    /**
     * @param CollectionFactory $materialCollectionFactory
     */
    public function __construct(CollectionFactory $materialCollectionFactory)
    {

        $this->materialCollectionFactory = $materialCollectionFactory;
    }

    public function getAllOptions()
    {
        $this->_options = [];
        $materialCollection = $this->materialCollectionFactory->create();
        foreach ($materialCollection as $material) {
            $this->_options[] = [
                'label' => $material->getData('material_category'),
                'value' => $material->getId()
            ];
        }

        return $this->_options;
    }
}