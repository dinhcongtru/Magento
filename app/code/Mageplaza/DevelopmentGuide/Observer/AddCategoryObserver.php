<?php
//namespace Mageplaza\DevelopmentGuide\Observer;
//
//use Magento\Framework\Event\ObserverInterface;
//use Magento\Catalog\Model\CategoryFactory;
//use Magento\Framework\App\ResourceConnection;
//use Magento\Framework\Event\Observer;
//use Magento\Framework\Message\ManagerInterface;
//use Mageplaza\DevelopmentGuide\Model\PostFactory;
//use Mageplaza\DevelopmentGuide\Model\CateFactory;
//class AddCategoryObserver implements ObserverInterface
//{
//    protected $_cateFactory;
//    protected $_resourceConnection;
//    protected $_messageManager;
//    protected $_postFactory;
//
//    public function __construct(CateFactory $cateFactory,
//                                ResourceConnection $resourceConnection,
//                                ManagerInterface $manager,
//                                PostFactory $postFactory
//    )
//
//    {
//        $this->_cateFactory = $cateFactory;
//        $this->_resourceConnection = $resourceConnection;
//        $this->_messageManager = $manager;
//        $this->_postFactory = $postFactory;
//    }
//
//    public function execute(\Magento\Framework\Event\Observer $observer)
//    {
//        $categoryId = $observer->getData('input_cateIds');
//        $data = $observer->getData('input_data');
//        // Check if the category already exists
//        $post = $this->_postFactory->create();
//
//        $validCategoryIds = [];
//        $flag = true;
//        $countA = false;
//        $countB = false;
//
//        foreach ($categoryId as $cateId) {
//            if ($this->getCateById($cateId)) {
//                $validCategoryIds[] = $cateId;
//
//                $countA = true;
//            }
//        }
//        foreach ($categoryId as $cateId) {
//            if (!$this->getCateById($cateId)) {
//
//                $category = $this->_cateFactory->create();
//
//                $dataCate = [
//                    'name' => "cate{$cateId}",
//                    'description' => "cate{$cateId}"
//                ];
//                $countB = true;
//                $flag = false;
//
//                $category->addData($dataCate)->save();
//                $getNewId = $category->getId();
//
//
//                $connection = $this->_resourceConnection->getConnection();
//                $tableName = $this->_resourceConnection->getTableName('mageplaza_blog_category');
//                $sql = "UPDATE {$tableName} SET `category_id` = '{$cateId}' WHERE `mageplaza_blog_category`.`category_id` = $getNewId;";
//                $connection->query($sql);
//
//
//
//            }
//        }
//        $validCategoryIds = array_unique($validCategoryIds);
//        $category = $this->_cateFactory->create()->load($categoryId);
//        if ($category->getId()) {
//            return;
//        }
//
//        // If the category doesn't exist, create it
//        $categoryData = [
//            'name' => 'New Category',
//            'is_active' => true,
//            'parent_id' => 2, // Set the parent category ID here
//            'include_in_menu' => true,
//            // Add any other category data here
//        ];
//        $category = $this->categoryFactory->create();
//        $category->setData($categoryData);
//        $category->save();
//    }
//    public function getCateById($cateId)
//    {
//        $cate = $this->_cateFactory->create();
//        $cate->load($cateId);
//
//        $result = $cate->getId();
//
//        return (bool) $result;
//    }
//}
