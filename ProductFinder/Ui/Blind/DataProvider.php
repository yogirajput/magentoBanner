<?php
namespace Yogesh\ProductFinder\Ui\Blind;

use Yogesh\ProductFinder\Model\ResourceModel\BlindCategory\CollectionFactory;
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
            if ($item->getBlindIcon()) {
                $blindTypeIcon['blind_icon'][0]['name'] = $item->getBlindIcon();
                $blindTypeIcon['blind_icon'][0]['url'] = '/media/blind_icon/icon'.$item->getBlindIcon();
                $fullData = $this->loadedData;
                $this->loadedData[$item->getId()] = array_merge($fullData[$item->getId()], $blindTypeIcon);
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