<?php

namespace Yogesh\ProductFinder\Model;

use Magento\Framework\Model\AbstractModel;
use Yogesh\ProductFinder\Model\ResourceModel\RoomCategory as RoomCategoryResourceModel;

class RoomCategory extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init(RoomCategoryResourceModel::class);
    }
}