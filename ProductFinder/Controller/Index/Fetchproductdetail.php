<?php


namespace Yogesh\ProductFinder\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Fetchprice
 * @package Yogesh\Matrixpricing\Controller\Index
 */
class Fetchproductdetail extends \Magento\Framework\App\Action\Action
{

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $jsonHelper;
    /**
     * @var DataFactory
     */
    private $dataHelper;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     */
    public function __construct(        
        \Magento\Framework\App\RequestInterface $requestInterface,
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
		\Yogesh\ProductFinder\Helper\Data $dataHelper
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->jsonHelper = $jsonHelper;
        parent::__construct($context);
        $this->request = $requestInterface;
		$this->dataHelper = $dataHelper;
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->request->getParams();		
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$priceHelper = $objectManager->create('Magento\Framework\Pricing\Helper\Data'); 
		$wishlistHelper = $objectManager->create('Magento\Wishlist\Helper\Data');
		$labelsHelper = $objectManager->create('Yogesh\Labels\Helper\Data');
		$cartHelper = $objectManager->create('Wyomind\ElasticsearchCore\Helper\Cart');
		$storeManager = $objectManager->get('Magento\Store\Model\StoreManagerInterface');
		$listBlock = $objectManager->get('\Magento\Catalog\Block\Product\ListProduct');
		$currentStore = $storeManager->getStore();
        $productcollection = $this->dataHelper->getProductList($data);
		$productlist = '';
			foreach($productcollection as $_product):
						
				$productlist .= '<li class="item product product-item">
					<div class="product-item-info product-landing-page" data-container="product-grid">
						<div class="image-fabric-container">
							<div class="owl-carousel owl-theme product-slide">';
							$wishlistAllow = $wishlistHelper->isAllow();
							$defaultLabel = $labelsHelper->getDefaultLabel1();
							$freecartimage = '/pub/media/catalog/product/'.$defaultLabel;
							$secondfreecartimage = '/pub/media/catalog/product'.$_product->getSecondStamp();
							/** @var string $uenc */
							$uenc = $cartHelper->getUenc();
							$FormKey = $objectManager->get('Magento\Framework\Data\Form\FormKey'); 
							$product = $objectManager->create('Magento\Catalog\Model\Product')->load($_product->getId());    
							
							$postParams = $listBlock->getAddToCartPostParams($_product);								
								$images = $product->getMediaGalleryImages();
								$productUrl = $currentStore->getBaseUrl().$_product->getUrlKey();
								$baseurlmedia = $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_STATIC);								
								foreach($images as $child){
									$productlist .= '<div class="item">
										<a href="'.$productUrl.'"
											class="product photo product-item-photo" tabindex="-1"><img src="'.$child->getUrl().'" width="432" height="454" > </a>
									</div>';								
								} 											
							$productlist .= '</div>
							<div class="actions-primary">
								<form class="product_addtocart_form"
											   data-role="tocart-form"
								  data-product-sku="'.$_product->getSku().'"
								  action="'.str_replace("productId", $_product->getId(), $cartHelper->getAddUrlPlaceHolder()).'"
											   method="post">
									<input type="hidden" name="product" value="'.$_product->getId().'">
									<input type="hidden" name="uenc" value="'.$uenc.'">
									<input type="hidden" name="productname"  value="'.$_product->getName().'" />
									<input type="hidden" name="unit" value="cm"/>
									<input type="hidden" name="operation" value="1"/>
									<input type="hidden" name="qty" value="1"/>
									<input type="hidden" name="Request" value="Free Sample"/>
								   <input type="hidden" name="isAjax" value="1">
								   <input name="form_key" type="hidden" value="'.$FormKey->getFormKey().'">';
								   
									$productlist .= '<div class="catalog_labels">';
									if (!empty($_product->getEnableFirstStamp()) && $_product->getEnableFirstStamp()): 
										$productlist .= '<div class="catalog_label catalog_label_1"
														 style="background-image: url('.$freecartimage.');">
												</div>';
									endif;
									/*if (!empty($_product->getEnableSecondStamp()) && $_product->getEnableSecondStamp() && !empty($_product->getSecondStamp()) && $_product->getSecondStamp()): 
										$productlist .= '<div class="catalog_label catalog_label_2 product-ribbion"
												 style="background-image: url('.$secondfreecartimage.');">
												 <span
												style="color:';if (!empty($_product->getFreeSampleOverlayColor())) {
													$attr = $_product->getResource()->getAttribute('free_sample_overlay_color');
													 if ($attr->usesSource()) {
														  $getOptionText = $attr->getSource()->getOptionText($_product->getFreeSampleOverlayColor());
													 }
													$productlist .= strtolower($getOptionText);
												} else {
													$productlist .= 'black';
												}$productlist .= '">
											'. __("Order Free Fabric Sample").'</span>
											</div>';
									endif;
									$productlist .= '<button type="button" 
											title="';
											if (!empty($_product->getQuantityAndStockStatusIds()) && $_product->getQuantityAndStockStatusIds() == 0) :
												$productlist .=__("The product is not available");
											else :
												$productlist .=__("Order Free Fabric Sample");
											endif;
											$productlist .='" class="eln-ajax-add-to-cart action tocart primary'; 
											if (!empty($_product->getQuantityAndStockStatusIds()) && $_product->getQuantityAndStockStatusIds() == 0) :
												$productlist .='disabled';
											endif; $productlist .='">
									</button>';*/
									
								$productlist .= '</div>
								   
								</form>
							</div>
						</div>';
						$productlist .= '<div class="product-tags">
								<ul class="list-reset marquee tagMarquee">';
									$productlist .= $this->_view->getLayout()->createBlock('Yogesh\CategoryHsp\Block\Widget\PriceBlock')->setProductEntityId($_product->getId())->setTemplate('Appnova_CategoryHsp::widget/pricelistblock.phtml')->toHtml();
									$deliverTime = ($_product->getDeliveryTime()) ? $_product->getDeliveryTime() : 0;
									$packagingTime = ($_product->getPackagingTime()) ? $_product->getPackagingTime() : 0;
									$manufactureTime = ($_product->getManufactureTime()) ? $_product->getManufactureTime() : 0;
									$deliverFrom = ($_product->getDeliveryFrom()) ? $_product->getDeliveryFrom() : 0;
									$totalDays = $deliverTime + $packagingTime + $manufactureTime;
									if ($totalDays > 0) { 
										$productlist .= '<li><img src="'. $baseurlmedia.'frontend/Dotcom/default/en_GB/images/icons/van.svg" alt="">
										Delivered in ';
										
										if (($deliverFrom > 0)) {
											$productlist .= $deliverFrom . ' to ';
										}
										
										$productlist .= $totalDays ;
										$productlist .= ' working days.
									</li>';
									 } 
									 if (!empty($_product->getMultipleOverlay())):
											$overlays = $_product->getMultipleOverlay();
											$overlayArr = explode(PHP_EOL, $overlays ?? '');
											foreach ($overlayArr as $overlay):
												$overlay = explode('|', ($overlay) ?? '');
												if (!empty($overlay[0])):
													if (!empty($overlay[1])): $overlayimg = trim(strtolower($overlay[1]));
													else: $overlayimg = ''; endif;
													$productlist .= '<li class="overlay-">' . html_entity_decode($overlayimg) . ' ' . strip_tags($overlay[0]) . '</li>';
												endif;
											endforeach;
										endif;
										
								$productlist .= '</ul>
							</div>
							<div class="product details product-item-details eln-product-item-details" product-id="'. $_product->getId().'">
							<div class="product-name-wishlist">
								<strong class="product name product-item-name">
									<a class="product-item-link"
										href="'.$productUrl.'">'.$_product->getName().'</a>
								</strong>
								<div data-role="add-to-links" class="actions-secondary">';
									if ($wishlistAllow) : 
									$productlist .= '<form data-role="towishlist-form"
										  action="javascript:void(0);"
										  method="post" class="towishlist-form">
									<input type="hidden" name="product" value="'.$_product->getId().'">
									<input name="form_key" type="hidden" value="'.$FormKey->getFormKey().'">
									<a onclick="addProductToWishlist('.$_product->getId().')"
									   title="'. __("Add to Wish List").'"
									   class="eln-ajax-add-to-wishlist action towishlist wishlist">
									</a>
								</form>';
								endif;
								$productlist .= '</div>
							</div>
							<div class="price-box price-final_price">
								<span class="price-container price-final_price tax weee">
									<span class="price-wrapper">
										<span class="regular-price">
											<span class="price">From '.$priceHelper->currency( $_product->getFinalPrice(), true, false).' </span>
										</span>
									</span>
								</span>
							</div>
						</div>
						
					</div>
					<script type="text/x-magento-init">
					{
						"[data-role=tocart-form], .form.map.checkout": {
							"catalogAddToCart": {
								"product_sku": "'.$_product->getSku().'"
							}
						}
					}
				</script>';
				//$productlist .= $this->_view->getLayout()->createBlock('Yogesh\Override\Block\Availablecolors')->setProductEntityId($_product->getId())->setKey($_product->getStyleCode())->setTemplate('Magento_Catalog::product/list/availablecolorslisting.phtml')->toHtml();
				
				$productlist .= '</li>';
			endforeach;
			$totalproduct = explode(',',$data['productids']);
			$totalpaged = ceil(count($totalproduct)/20);
			$pagination = '';
			$paged = $data['paged'];
			//$counter = 1;
			if($totalpaged >1){
			$pagination .= '<div class="pager"><p class="toolbar-amount"><span class="toolbar-number">Items <span id="startpage">'.(20*$data['paged']-19).'<span> to <span id="endpage">'.(20*$data['paged']).'</span> of '.count($totalproduct).' total</span>
			</p><div class="pages">
				<ul class="items pages-items" aria-labelledby="paging-label">';
				if($paged > 1 ){
					$pagination .= '<li class="item pages-item-previous"><a class="action  previous pagination_finder" href="javascript:void(0);"  data-pageid="'.($paged-1).'" title="Previous"><span>Previous</span></a></li>';
				}
				if ($paged > 3):
					$pagination .= '<li class="item"><strong class="page"><a href="javascript:void(0);" class="pagination_finder" data-pageid="1"><span>1</span></a></strong></li>
					<li class="item">...</li>';
				endif;
				if ($paged-2 > 0):
					$pagination .= '<li class="item"><strong class="page"><a href="javascript:void(0);" class="pagination_finder" data-pageid="'.($paged-2).'"><span>'.($paged-2).'</span></a></strong></li>';
				 endif;
				if ($paged-1 > 0):
					$pagination .= '<li class="item"><strong class="page"><a href="javascript:void(0);" class="pagination_finder" data-pageid="'.($paged-1).'"><span>'.($paged-1).'</span></a></strong></li>';
				endif;
					$pagination .= '<li class="item current"><strong class="page"><span>'.$paged.'</span></strong></li>';
				if ($paged+1 < $totalpaged+1):
					$pagination .= '<li class="item"><strong class="page"><a href="javascript:void(0);" class="pagination_finder" data-pageid="'.($paged+1).'"><span>'.($paged+1).'</span></a></strong></li>';
				 endif;
				if ($paged+2 < $totalpaged+1):
					$pagination .= '<li class="item"><strong class="page"><a href="javascript:void(0);" class="pagination_finder" data-pageid="'.($paged+2).'"><span>'.($paged+2).'</span></a></strong></li>';
				endif;
				if ($paged < $totalpaged-2):
					$pagination .= '<li class="item">...</li>
					<li class="item"><strong class="page"><a href="javascript:void(0);" class="pagination_finder" data-pageid="'.$totalpaged.'"><span>'.$totalpaged.'</span></a></strong></li>';
				endif;
				
				if($paged<$totalpaged){ 
					$pagination .= '<li class="item pages-item-next"><a class="action pagination_finder  next" href="javascript:void(0);"  data-pageid="'.($paged+1).'" title="Next"><span>Next</span></a></li>';
				}
				$pagination .= '</ul>
			</div></div>';
			}
			$jsonData = ['result' => ['status' => 200, 'productlist' => $productlist, 'pagination'=>$pagination]];
        try {
            return $this->jsonResponse($jsonData);
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            return $this->jsonResponse($e->getMessage());
        } catch (\Exception $e) {
            $this->logger->critical($e);
            return $this->jsonResponse($e->getMessage());
        }
    }

    /**
     * Create json response
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function jsonResponse($response = '')
    {
        return $this->getResponse()->representJson(
            $this->jsonHelper->jsonEncode($response)
        );
    }
}
