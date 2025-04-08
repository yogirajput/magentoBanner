<?php
namespace Yogesh\Banner\Model\Banner\Source;

class IsActive implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var \Yogesh\Finetune\Model\Finetune
     */
    protected $banner;

    /**
     * Constructor
     *
     * @param \Yogesh\Finetune\Model\Status $finetune
     */
    public function __construct(\Yogesh\Banner\Model\Status $banner)
    {
        $this->banner = $banner;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options[] = ['label' => '', 'value' => ''];
        $availableOptions = $this->banner->getOptionArray();
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}
