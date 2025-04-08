<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Created By : Rohan Hapani
 */
namespace Yogesh\ProductFinder\Model\ResourceModel\RoomCategory;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Yogesh\ProductFinder\Model\RoomCategory as RoomCategoryModel;
use Yogesh\ProductFinder\Model\ResourceModel\RoomCategory as RoomCategoryResourceModel;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(RoomCategoryModel::class, RoomCategoryResourceModel::class);
    }
}