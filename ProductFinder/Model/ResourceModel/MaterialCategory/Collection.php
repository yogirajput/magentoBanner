<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Created By : Rohan Hapani
 */
namespace Yogesh\ProductFinder\Model\ResourceModel\MaterialCategory;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Yogesh\ProductFinder\Model\MaterialCategory as MaterialCategoryModel;
use Yogesh\ProductFinder\Model\ResourceModel\MaterialCategory as MaterialCategoryResourceModel;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(MaterialCategoryModel::class, MaterialCategoryResourceModel::class);
    }
}