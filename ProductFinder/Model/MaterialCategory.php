<?php

namespace Yogesh\ProductFinder\Model;

use Magento\Framework\Model\AbstractModel;
use Yogesh\ProductFinder\Model\ResourceModel\MaterialCategory as MaterialCategoryResourceModel;

class MaterialCategory extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init(MaterialCategoryResourceModel::class);
    }
}