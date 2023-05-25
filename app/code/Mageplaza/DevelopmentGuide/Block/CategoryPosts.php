<?php

namespace Mageplaza\DevelopmentGuide\Block;

use Magento\Framework\View\Element\Template;

class CategoryPosts extends Template
{
//    protected $_registry;

    protected $_postFactory;

    public function __construct(
        Template\Context                              $context,
        \Mageplaza\DevelopmentGuide\Model\PostFactory $postFactory
    )
    {
        $this->_postFactory = $postFactory;
        parent::__construct($context);
    }

//    public function getCategoryName()
//    {
//        $category = $this->_registry->registry('current_category');
//        if ($category) {
//            return __('Category: %1', $category->getName());
//        }
//    }
    public function getPostCollection()
    {
        $post = $this->_postFactory->create();
        return $post->getCollection();
    }
}
