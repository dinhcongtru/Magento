<?php

namespace Mageplaza\DevelopmentGuide\Controller\Adminhtml\Model;

use Magento\Framework\App\Action\Context;

class Crud extends \Magento\Framework\App\Action\Action
{
    protected $_postFactory;

    public function __construct(context                                       $context,
                                \Mageplaza\DevelopmentGuide\Model\PostFactory $postFactory
    )
    {
        $this->_postFactory = $postFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $post = $this->_postFactory->create();
        $data = [
            'name' => "How to Create SQL Setup Script in Magento 1",
            'post_content' => "In this article, we will find out how to install and upgrade sql script for module in Magento 2. When you install or upgrade a module, you may need to change the database structure or add some new data for current table. To do this, Magento 2 provide you some classes which you can do all of them.",
            'url_key' => '/magento-2-module-development/magento-2-how-to-create-sql-setup-script.html',
            'tags' => 'magento 2,mageplaza devdlopmentguide',
            'categories' => 'categories blog',
            'status' => 1
        ];

        try {
            if ($post->addData($data)->save()) {
                echo "added successfully";
            } else {
                echo "error";
            }
        } catch (\Exception $e) {
            echo "Error!!";
        }
    }
}
