<?php
namespace Yogesh\Insurance\Controller\Index;

use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Data\Form\FormKey;
use Magento\Checkout\Model\Cart;
use Magento\Catalog\Model\Product;

class Session extends \Magento\Framework\App\Action\Action
{
    protected $_dataHelper;
    protected $_formKeyValidator;
	protected $resultJsonFactory;
	public function __construct(
		\Magento\Framework\App\Action\Context $context,
        \Yogesh\Insurance\Helper\Data $dataHelper,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
	){
		$this->resultJsonFactory = $resultJsonFactory;
        $this->_dataHelper = $dataHelper;
        $this->_formKeyValidator = $formKeyValidator ?: ObjectManager::getInstance()->get(Validator::class);
		return parent::__construct($context);
	}

	public function execute()
	{ 
		$post = $this->getRequest()->getPostValue();
		if ($post && $this->isAjax() && $this->_formKeyValidator->validate($this->getRequest())) {
			if (isset($post['insurance'])) {
				$flag = (bool)$post['insurance'];
			} else {
				$flag = false;
			}
			$success = true;
			if ($flag) {
				$this->_dataHelper->addToSession();
				$message = 'Flag added to session';
			} else {
				$this->_dataHelper->removeFromSession();
				$message = 'Flag removed from session';
			}			
		} else {
			//TODO: Manage errors
			$success = false;			
			$message = 'Sorry, something wrong';			
		}
		$resultJson = $this->resultJsonFactory->create();
		$response = ['success' => $success, 'message' => ''.$message, 'current' => $this->_dataHelper->checkInsurance()];
		$resultJson->setData($response);
		return $resultJson;
	}

    /*
     *  Check Request is Ajax or not
     * @return boolean
     * */
    protected function isAjax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }
}