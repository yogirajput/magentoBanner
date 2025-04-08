<?php


namespace Yogesh\ProductFinder\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Fetchprice
 * @package Yogesh\Matrixpricing\Controller\Index
 */
class Fetchproductcolorlist extends \Magento\Framework\App\Action\Action
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
		$productlist = '';
		$resultPage = $this->resultPageFactory->create();
			$productlist = $resultPage->getLayout()->createBlock('Yogesh\Override\Block\Availablecolors')->setProductEntityId($data['productid'])->setKey($data['stylecode'])->setTemplate('Magento_Catalog::product/list/availablecolorslisting.phtml')->toHtml();
			$jsonData = ['result' => ['status' => 200, 'productlist' => $productlist]];
			
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
