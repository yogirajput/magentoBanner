<?php

namespace Yogesh\ProductFinder\Model;

use Yogesh\ProductFinder\Model\ResourceModel\RoomCategory\CollectionFactory;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * @var CollectionFactory
     */
    private  $roomCategoryCollectionFactory;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $roomCategoryCollectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->roomCategoryCollectionFactory = $roomCategoryCollectionFactory;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);

    }

    // @codingStandardsIgnoreEnd

    public function getData()
    {

        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $items = $this->roomCategoryCollectionFactory->create()->getItems();

        foreach ($items as $roomCategory) {
            $this->loadedData[$roomCategory->getId()] = $roomCategory->getData();
        }
        return $this->loadedData;
    }
}