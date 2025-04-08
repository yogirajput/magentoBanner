<?php
namespace Yogesh\Faq\Model\ResourceModel;

class Faqgroup extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('faqgroup', 'id');
    }
}
?>