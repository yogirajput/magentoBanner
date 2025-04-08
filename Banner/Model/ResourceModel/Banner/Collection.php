<?php
namespace Yogesh\Banner\Model\ResourceModel\Banner;

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
        $this->_init('Yogesh\Banner\Model\Banner', 'Yogesh\Banner\Model\ResourceModel\Banner');
        $this->_map['fields']['page_id'] = 'main_table.page_id';
    }

}
?>