<?php

namespace Mageplaza\DevelopmentGuide\Controller\Post;
/**
 * @var \Mageplaza\DevelopmentGuide\Block\Form\Category $block
 */

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResourceConnection;
use Mageplaza\DevelopmentGuide\Block\Form\Category;
use Mageplaza\DevelopmentGuide\Model\CateFactory;

class Cate extends \Magento\Framework\App\Action\Action
{
    protected $_cateFactory;
    protected $_block;
    protected $_resourceConnection;

    public function __construct(context                                       $context,
                                \Mageplaza\DevelopmentGuide\Model\CateFactory $cateFactory, Category $block, ResourceConnection $resourceConnection)
    {
        $this->_cateFactory = $cateFactory;
        $this->_block = $block;
        $this->_resourceConnection = $resourceConnection;
        parent::__construct($context);
    }

    public function execute()
    {
        $cate = $this->_cateFactory->create();
        $data = [
            'name' => $this->getRequest()->getParam('name'),
            'description' => $this->getRequest()->getParam('description')
        ];
        //  validate urlKey Unique
//        $url_key = [];
//        foreach ($this->_block->getPostCollection() as $postUrl){
//            $url_key[] = $postUrl->getUrlKey();
//        }
//
//        if(in_array($data['url_key'], $url_key)){
//            $this->messageManager->addErrorMessage('URL key already exists.');
//            return $this->_redirect('*/index/category/');
//        }

//            validate input post categories
        if (isset($data['id'])) {
            $cate->Load($data['id']);
            $this->_forward('save');
        } else {
            $cate->addData($data);
            try {
                if ($cate->addData($data)->save()) {
                    $this->messageManager->addSuccessMessage('Category added successfully.');
                    $this->_redirect('*/post/save');
                } else {
                    $this->messageManager->addErrorMessage('Category not added successfully.');
                    $this->_redirect('*/index/category');
                }
            } catch (Exception $e) {
                $this->messageManager->addExceptionMessage('Category added failed' . $e->getMessage());
            }
        }


        $categories = $this->getRequest()->getParam('categories');
        if (!preg_match('/^\d+(,\s*\d+)*$/', $categories)) {
            $this->messageManager->addErrorMessage('Invalid categories!');
            return $this->_redirect('*/index/category/');
        }

        try {
            $post->addData($data)->save();
            $this->messageManager->addSuccessMessage('post category added successfully');
            return $this->_redirect('*/index/category/');
        } catch (\Exception $e) {
            echo "Error!!";
        }
    }

}
