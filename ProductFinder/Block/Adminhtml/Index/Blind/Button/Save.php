<?php
namespace Yogesh\ProductFinder\Block\Adminhtml\Index\Blind\Button;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Ui\Component\Control\Container;

class Save extends Generic implements ButtonProviderInterface
{
    /**
     * Save Button Creation
     *
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Save'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => [
                    'buttonAdapter' => [
                        'actions' => [
                            [
                                'targetName' => 'blind_type_form.blind_type_form',
                                'actionName' => 'save',
                                'params' => [
                                    'action' => 'save'
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'class_name' => Container::SPLIT_BUTTON,
            'options' => $this->getOptions(),
            'sort_order' => 90,
        ];
    }

    /**
     * Retrieve options
     *
     * @return array
     */
    private function getOptions()
    {
        $options = [
            [
                'id_hard' => 'submit_only',
                'label' => __('Save'),
                'data_attribute' => [
                    'mage-init' => [
                        'buttonAdapter' => [
                            'actions' => [
                                [
                                    'targetName' => 'blind_type_form.blind_type_form',
                                    'actionName' => 'save',
                                    'params' => [
                                        'action' => 'submit'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
            ],
            [
                'label' => __('Save & Continue'),
                'id_hard' => 'save_and_continue',
                'data_attribute' => [
                    'mage-init' => [
                        'buttonAdapter' => [
                            'actions' => [
                                [
                                    'targetName' => 'blind_type_form.blind_type_form',
                                    'actionName' => 'save',
                                    'params' => [
                                        true,
                                        [
                                            'action' => 'continue'
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
            ]

        ];
        return $options;
    }
}