<?php
namespace Yogesh\Feature\Model;

class Feature extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Yogesh\Feature\Model\ResourceModel\Feature');
    }
}
?>