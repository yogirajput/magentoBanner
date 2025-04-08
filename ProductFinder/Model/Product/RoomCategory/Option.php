<?php

namespace Yogesh\ProductFinder\Model\Product\RoomCategory;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Yogesh\ProductFinder\Model\ResourceModel\RoomCategory\CollectionFactory;

class Option extends AbstractSource
{
    protected $optionFactory;

    /**
     * @var CollectionFactory
     */
    private $roomCollectionFactory;

    /**
     * @param CollectionFactory $roomCollectionFactory
     */
    public function __construct(CollectionFactory $roomCollectionFactory)
    {

        $this->roomCollectionFactory = $roomCollectionFactory;
    }

    public function getAllOptions()
    {
        $this->_options = [];
        $roomTypeCollection = $this->roomCollectionFactory->create();
        foreach ($roomTypeCollection as $roomType) {
            $this->_options[] = [
                'label' => $roomType->getData('room_category'),
                'value' => $roomType->getId()
            ];
        }

        return $this->_options;
    }
}