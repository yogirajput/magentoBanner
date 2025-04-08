<?php
namespace Yogesh\Faq\Model;

class Faq extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Yogesh\Faq\Model\ResourceModel\Faq');
    }
}
?>