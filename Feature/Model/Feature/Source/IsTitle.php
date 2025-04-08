<?php
namespace Yogesh\Feature\Model\Feature\Source;

class IsTitle implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var \Yogesh\Feature\Model\Feature
     */
    protected $eavConfig;

    /**
     * Constructor
     *
     * @param \Yogesh\Feature\Model\Status $feature
     */
    public function __construct(\Magento\Eav\Model\Config $eavConfig)
    {
        $this->eavConfig = $eavConfig;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options[] = ['label' => '', 'value' => ''];
        $attribute = $this->eavConfig->getAttribute('catalog_product', 'shopfeature');
		$Values = $attribute->getSource()->getAllOptions();
		foreach($Values as $data)
		{
			if(!empty($data['value']))
			{
				$options[] = [
                'label' => $data['label'],
                'value' => $data['value'],
            ];
			}
		}
        return $options;
    }
}
