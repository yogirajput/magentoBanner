<?php
namespace Yogesh\Feature\Model\Feature\Source;

class IsActive implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var \Yogesh\Feature\Model\Feature
     */
    protected $feature;

    /**
     * Constructor
     *
     * @param \Yogesh\Feature\Model\Status $feature
     */
    public function __construct(\Yogesh\Feature\Model\Status $feature)
    {
        $this->feature = $feature;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options[] = ['label' => '', 'value' => ''];
        $availableOptions = $this->feature->getOptionArray();
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}
