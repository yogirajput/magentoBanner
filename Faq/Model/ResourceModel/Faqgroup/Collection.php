<?php
namespace Yogesh\Faq\Model\ResourceModel\Faqgroup;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Yogesh\Faq\Model\Faqgroup', 'Yogesh\Faq\Model\ResourceModel\Faqgroup');
        $this->_map['fields']['page_id'] = 'main_table.page_id';
    }

}
?>