<?php
namespace Yogesh\Faq\Model\ResourceModel\Faq;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    protected $_idFieldName = 'faq_id';
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Yogesh\Faq\Model\Faq', 'Yogesh\Faq\Model\ResourceModel\Faq');
    }

}
?>