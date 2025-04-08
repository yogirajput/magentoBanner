<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Created By : Rohan Hapani
 */
namespace Yogesh\ProductFinder\Model\ResourceModel\BlindCategory;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Yogesh\ProductFinder\Model\BlindCategory as BlindCategoryModel;
use Yogesh\ProductFinder\Model\ResourceModel\BlindCategory as BlindCategoryResourceModel;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(BlindCategoryModel::class, BlindCategoryResourceModel::class);
    }
}