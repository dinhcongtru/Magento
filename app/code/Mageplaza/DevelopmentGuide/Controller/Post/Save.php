<?php

namespace Mageplaza\DevelopmentGuide\Controller\Post;
/**
 * @var \Mageplaza\DevelopmentGuide\Block\Form\Category $block
 */

use JMS\Serializer\Exception\Exception;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResourceConnection;
use Mageplaza\DevelopmentGuide\Block\Form\Category;
use Mageplaza\DevelopmentGuide\Model\PostFactory;
use Mageplaza\DevelopmentGuide\Model\CateFactory;
use function Symfony\Component\String\s;

class Save extends \Magento\Framework\App\Action\Action
{
    protected $_postFactory;
    protected $_block;
    protected $_cateFactory;
    protected $_resourceConnection;

    public function __construct(context                                       $context,
                                \Mageplaza\DevelopmentGuide\Model\PostFactory $postFactory,
                                \Mageplaza\DevelopmentGuide\Model\CateFactory $cateFactory,
                                Category                                      $block, ResourceConnection $resourceConnection)
    {
        $this->_postFactory = $postFactory;
        $this->_cateFactory = $cateFactory;
        $this->_block = $block;
        $this->_resourceConnection = $resourceConnection;
        parent::__construct($context);
    }

    public function execute()
    {
        if (!$this->validateData()) {
            $data = [
                'name' => $this->getRequest()->getParam('name'),
                'post_content' => $this->getRequest()->getParam('post_content'),
                'url_key' => $this->getRequest()->getParam('url_key'),
                'tags' => $this->getRequest()->getParam('tags'),
                'categories' => $this->getRequest()->getParam('categories')

            ];

            $cate = $this->_cateFactory->create();
            $idsCate = $cate->getCollection()->getAllIds();
            $categories = $this->getRequest()->getParam('categories');
//        Xử lý khi người dùng thêm categoris
            $arrayCate = array_map('trim', explode(',', $categories));
            $intersect = array_intersect($arrayCate, $idsCate);
            $diff = array_diff($arrayCate, $idsCate);

            if (!empty($intersect)) {
                $post = $this->_postFactory->create();
                $data['categories'] = implode(',', $intersect);
                $post->addData($data)->save();
                $this->_redirect('*/index/category/');
            }
            if (count($diff) != 0) {
                foreach ($diff as $item) {
                    $cate = $this->_cateFactory->create();

                    $cateData = [
                        'name' => 'CategoryName' . $item,
                        'description' => 'description category !!!!!'
                    ];

                    $cate->addData($cateData)->save();
                    $getId = $cate->getId();
                    $connection = $this->_resourceConnection->getConnection();
                    $tableName = $this->_resourceConnection->getTableName('mageplaza_blog_category');
                    $sql = "UPDATE {$tableName} SET `category_id` = '{$item}' WHERE `mageplaza_blog_category`.`category_id` = $getId";
                    $connection->query($sql);
                }
            }

            $this->messageManager->addSuccessMessage('category added successfully');
            $this->_redirect('*/index/category/');
        }


    }

    public function validateData()
    {
        $data = [
            'name' => $this->getRequest()->getParam('name'),
            'post_content' => $this->getRequest()->getParam('post_content'),
            'url_key' => $this->getRequest()->getParam('url_key'),
            'tags' => $this->getRequest()->getParam('tags'),
            'categories' => $this->getRequest()->getParam('categories')

        ];
        //  validate urlKey Unique
        $url_key = [];
        foreach ($this->_block->getPostCollection() as $postUrl) {
            $url_key[] = $postUrl->getUrlKey();
        }

        if (in_array($data['url_key'], $url_key)) {
            $this->messageManager->addErrorMessage('URL key already exists.');
            return $this->_redirect('*/index/category/');
        }

        $cate = $this->_cateFactory->create();
        $idsCate = $cate->getCollection()->getAllIds();
        $categories = $this->getRequest()->getParam('categories');

        if (!preg_match('/^\d+(,\s*\d+)*$/', $categories)) {
            $this->messageManager->addErrorMessage('Invalid categories!');
            return $this->_redirect('*/index/category/');
        }
    }

}
