<?php

namespace Yogesh\ProductFinder\Model\Product\BlindCategory;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Yogesh\ProductFinder\Model\ResourceModel\BlindCategory\CollectionFactory;

class Option extends AbstractSource
{
    protected $optionFactory;

    /**
     * @var CollectionFactory
     */
    private $blindCollectionFactory;

    /**
     * @param CollectionFactory $blindCollectionFactory
     */
    public function __construct(CollectionFactory $blindCollectionFactory)
    {

        $this->blindCollectionFactory = $blindCollectionFactory;
    }

    public function getAllOptions()
    {
        $this->_options = [];
        $blindTypeCollection = $this->blindCollectionFactory->create();
        foreach ($blindTypeCollection as $blindType) {
            $this->_options[] = [
                'label' => $blindType->getData('blind_category'),
                'value' => $blindType->getId()
            ];
        }

        return $this->_options;
    }
}