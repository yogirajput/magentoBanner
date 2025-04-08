<?php
namespace Yogesh\ProductFinder\Ui;

use Yogesh\ProductFinder\Model\ResourceModel\RoomCategory\CollectionFactory;
use Magento\Framework\UrlInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;

class DataProvider extends AbstractDataProvider
{
    protected $loadedData;
    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * UI Component Data Provider
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param UrlInterface $urlBuilder
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        UrlInterface $urlBuilder,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Read Data function
     *
     * @return array
     */
    public function getData()
    {
        if ($this->loadedData) {
            return $this->loadedData;
        }
        foreach ($this->collection->getItems() as $item) {
            $this->loadedData[$item->getId()] = $item->getData();
            if ($item->getRoomIcon()) {
                $roomTypeIcon['room_icon'][0]['name'] = $item->getRoomIcon();
                $roomTypeIcon['room_icon'][0]['url'] = '/media/room_type/icon'.$item->getRoomIcon();
                $fullData = $this->loadedData;
                $this->loadedData[$item->getId()] = array_merge($fullData[$item->getId()], $roomTypeIcon);
            }
        }
        return $this->loadedData;
    }

    /**
     * Get Meta Data
     *
     * @return array
     */
    public function getMeta()
    {
        $meta = parent::getMeta();
        return $meta;
    }
}