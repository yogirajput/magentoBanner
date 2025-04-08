<?php

namespace Yogesh\Labels\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    private $productLoader;
    private $storeManager;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Catalog\Model\ProductFactory $_productloader,
        \Magento\Store\Model\StoreManagerInterface $storeManager) {
        $this->productLoader = $_productloader;
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    function getBaseImageUrl()
    {
        $currentStore = $this->storeManager->getStore();
        $mediaUrl = $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        return $mediaUrl;
    }

    function getLabelUrl($label = '') {
        return $this->getBaseImageUrl().'catalog/product'.$label;
    }

    function getDefaultLabel1() {
        $label = $this->scopeConfig->getValue('dotcom_category_settings/appnova_labels/appnova_label_1', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if (!$label || is_null($label) || '' == $label) {
            return false;
        }
        return '/'.$label;
    }

   public function getLabels($product = false) {

        if (!$product) {
            return false;
        }

        //Load product by product id
        $product = $this->productLoader->create()->load($product->getId());

        $defaultLabel1 = $this->getDefaultLabel1();

        $label1 = $product->getData('first_stamp');
        $label2 = $product->getData('second_stamp');
        $enableFirstStamp = $product->getData('enable_first_stamp');
        $enableSecondStamp = $product->getData('enable_second_stamp');

        $labels = array(false, false);
        if ($enableFirstStamp == 1 ) {

            if (!is_null($label1) && '' != $label1) {
                $labels[0] = $label1;
            } else {
                $labels[0] = $defaultLabel1;
            }
            
        }
        if ($enableSecondStamp == 1) {
            if (!is_null($label2) && '' != $label2) {
                $labels[1] = $label2;
            } else {
                //No default label
            }
        }

        foreach ($labels as $key => $value) {
            if ($value && ''!= $value) {
                $labels[$key] = $this->getLabelUrl($value);
            }
        }

        return $labels;
   }

   public function echoLabels($product = false) {
        $labels = $this->getLabels($product);
        if (!$labels || (!$labels[0] && !$labels[1])) {
            return;
        }

        $alignment = array('top left', 'bottom right');
        $zindex = 10;
        echo '<div class="catalog_labels">';
        foreach ($labels as $key => $value) {
            if ($value) {
                $zindex++;
                echo '<div class="catalog_label catalog_label_'.($key+1).'" style="background-image: url('.$value.');">';
                echo '</div>';
            }
        }
        echo '</div>';

        return;
   }
		
}
