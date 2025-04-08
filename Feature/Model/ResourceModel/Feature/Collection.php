<?php
namespace Yogesh\Feature\Model\ResourceModel\Feature;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	 protected $_idFieldName = 'id';
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Yogesh\Feature\Model\Feature', 'Yogesh\Feature\Model\ResourceModel\Feature');
     }

}
?>