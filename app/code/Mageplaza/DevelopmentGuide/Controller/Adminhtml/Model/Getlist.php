<?php

namespace Mageplaza\DevelopmentGuide\Controller\Adminhtml\Model;

use Magento\Framework\App\Action\Context;

class Getlist extends \Magento\Framework\App\Action\Action
{
    protected $_postFactory;

    public function __construct(Context                                       $context,
                                \Mageplaza\DevelopmentGuide\Model\PostFactory $postFactory
    )
    {
        parent::__construct($context);
        $this->_postFactory = $postFactory;
    }

    public function execute()
    {
        $post = $this->_postFactory->create();
        $conlection = $post->getCollection();
        echo "<pre>";
        print_r($conlection->getData());
        echo "<pre>";
    }
}
