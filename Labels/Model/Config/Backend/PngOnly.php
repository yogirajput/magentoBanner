<?php
namespace Yogesh\Labels\Model\Config\Backend;
 
class PngOnly extends \Magento\Config\Model\Config\Backend\Image
{
    /**
     * @return string[]
     */
    public function getAllowedExtensions() {
        return ['png'];
    }
}