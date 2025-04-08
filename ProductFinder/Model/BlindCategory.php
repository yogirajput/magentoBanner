<?php

namespace Yogesh\ProductFinder\Model;

use Magento\Framework\Model\AbstractModel;
use Yogesh\ProductFinder\Model\ResourceModel\BlindCategory as BlindCategoryResourceModel;

class BlindCategory extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init(BlindCategoryResourceModel::class);
    }
}