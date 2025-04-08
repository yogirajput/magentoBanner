<?php
namespace Yogesh\Faq\Model\Faq\Source;

class IsActive implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var \Yogesh\Faq\Model\Faq
     */
    protected $faq;

    /**
     * Constructor
     *
     * @param \Yogesh\Faq\Model\Status $faq
     */
    public function __construct(\Yogesh\Faq\Model\Status $faq)
    {
        $this->faq = $faq;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options[] = ['label' => '', 'value' => ''];
        $availableOptions = $this->faq->getOptionArray();
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}
