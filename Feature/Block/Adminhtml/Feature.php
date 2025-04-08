<?php
namespace Yogesh\Feature\Block\Adminhtml;

class Feature extends \Magento\Backend\Block\Widget\Container
{
     protected function _construct()
    {
        $this->_controller = 'adminhtml_feature';
        $this->_blockGroup = 'Appnova_Feature';
        $this->_headerText = __('Manage Feature');

        parent::_construct();

        if ($this->_isAllowedAction('Appnova_Feature::save')) {
            $this->buttonList->update('add', 'label', __('Add New Feature'));
        } else {
            $this->buttonList->remove('add');
        }
    }

    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}
