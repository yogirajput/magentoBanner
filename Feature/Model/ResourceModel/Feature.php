<?php
namespace Yogesh\Feature\Model\ResourceModel;

class Feature extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('appnova_feature', 'id');
    }
}
?>