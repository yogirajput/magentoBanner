<?php
namespace Yogesh\Faq\Model;

class Faqgroup extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Yogesh\Faq\Model\ResourceModel\Faqgroup');
    }
}
?>