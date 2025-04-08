<?php
namespace Yogesh\ProductSale\Console;

use Magento\Framework\App\State;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Model\ProductFactory;
use Symfony\Component\Console\Input\InputOption;
use Magento\Framework\App\Area;

class Generate extends Command
{
    /**
     * @var CollectionFactory
     */
    private $productCollectionFactory;
	
	
    protected $_categoryRepository;
    /**
     * @var ProductFactory
     */
    private $productFactory;

    /**
     * @var State
     */
    private $state;
	const CreateProductSale = 'createproductsale';
    /**
     * @param CollectionFactory $collectionFactory
     * @param ProductFactory $productFactory
     * @param State $state
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        ProductFactory $productFactory,		
        \Magento\Catalog\Model\CategoryRepository $categoryRepository,
        State $state
    )
    {
        parent::__construct('product:product-sale:generate');
        $this->productCollectionFactory = $collectionFactory;
        $this->productFactory = $productFactory;		
        $this->_categoryRepository = $categoryRepository;
        $this->state = $state;
    }

    protected function configure()
    {
         $options = [
			new InputOption(
				self::CreateProductSale,
				null,
				InputOption::VALUE_REQUIRED,
				'CreateProductSale'
			)
		];
        $this->setName('product:product-sale:generate');
        $this->setDescription('Generate Product Sale based on category HSP For Products');
        $this->setDefinition($options);
        parent::configure();
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {	$CreateProduct = $input->getOption(self::CreateProductSale);
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$dateTimeFormatter = $objectManager->get('Magento\Framework\Stdlib\DateTime\DateTimeFormatterInterface');
		//$staticDate = new \DateTime($OrderCreatedDate);
		$datetime = new \DateTime('now', new \DateTimeZone('Europe/London'));
	
		$endDate = $datetime->format('Y-m-d H:i:s');
		$datetime1 = (new \DateTime())->setTimestamp(strtotime($endDate." -12 hours"));
		$startDate = $datetime1->format('Y-m-d H:i:s');
		$catids  = [202];
        $this->state->setAreaCode(Area::AREA_ADMINHTML);
        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToFilter('status', 1);
        $collection->addAttributeToSelect('visibility', 4);
		$collection->addCategoriesFilter(['in' => $catids]);
        //$collection->addAttributeToFilter('appnova_product_sale', ['eq' => '']); //Attribute Code
		//if($CreateProduct == 1 ){
			//$collection->addAttributeToFilter('updated_at', array('from'=>$startDate, 'to'=>$endDate));
			//$collection->setOrder('updated_at','ASC');
	//	}
		$collection->setOrder('appnova_product_sale','ASC');
		//echo $collection->getSelect()->__toString();
		$collection->setPageSize(10);
		//echo $collection->count(); 
		//exit;
		//echo "<pre>"; print_r($collection->getData()); exit;
        if ($collection->count() > 0) {
            foreach ($collection as $product) {
                    $output->writeln('<info>Start Product Sale Update For '.$product->getId().'</info>');
                    $productsale = $this->getProductHspList($product->getId());
					if($productsale !='' && $productsale > 0){
                        $productModel = $this->productFactory->create()->load($product->getId());
                        $productModel->setData('appnova_product_sale', $productsale);
						 $productModel->save();
						 $productActionObject = $objectManager->create('Magento\Catalog\Model\Product\Action');
						 $proId = array($product->getId());		
                        $productActionObject->updateAttributes($proId, array('appnova_product_sale' => $productsale), 0);
                      // $productActionObject->save();
						$output->writeln('<info>End Product Sale Update For '.$product->getId().'</info>');
					}else{
						$output->writeln('<info>End Product Sale not Update For '.$product->getId().'</info>');
					}
                    
                
            }
        }
    }
	
	/**
     * @param $product
     * @return mixed
     */
    private function getProductFromId($product)
    {
        $productModel = $this->productFactory->create();
        return $productModel->load($product);
    }
	
    private function getProductHspList($productID) {

       $productID = $productID;

        //Could not be needed to reload... jic for now
        $product = $this->getProductFromId($productID);

        if (!$product) {
            return false;
        }

        $categoryIds = $product->getCategoryIds();

        $hspp_min = false;

        foreach ($categoryIds as $categoryId) {
            $category = $this->_categoryRepository->get($categoryId);
            $children = $category->getChildrenCategories();
            $continue = false;
            foreach ($children as $child) {
                if (in_array($child->getId(), $categoryIds)) {
                    //One of the children is assigned to the product, ignore this category
                    $continue = true;
                    break;
                }
            }
            if ($continue) {
                continue;
            }
            if ($category) {
                $hspp = trim(str_replace('%', '', (string)$category->getHspPercentage()));
                if (!is_null($hspp) && false !== $hspp && ''.$hspp != '' && ($hspp_min === false || (float)$hspp < $hspp_min)) {
                    $hspp_min = (float)$hspp;
                }
            }
        }

        $hspp_min = round((float)$hspp_min*100)/100;
		
		return $hspp_min;
		
        

    }
}