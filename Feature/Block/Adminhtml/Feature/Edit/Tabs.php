<?php
namespace Yogesh\Feature\Block\Adminhtml\Feature\Edit;

/**
 * Admin page left menu
 */
class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('feature_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Feature Information'));
    }
}
