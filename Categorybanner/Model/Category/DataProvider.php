<?php

namespace Yogesh\Categorybanner\Model\Category;

class DataProvider extends \Magento\Catalog\Model\Category\DataProvider
{
    protected function getFieldsMap()
    {
        $fields = parent::getFieldsMap();
        /*$fields['content'][] = 'custom_image_1';
        $fields['content'][] = 'custom_image_2';
        $fields['content'][] = 'custom_image_3';
        $fields['content'][] = 'custom_image_4';*/
        return $fields;
    }
}