<?php

namespace Mageplaza\DevelopmentGuide\Controller\Adminhtml\Model;

use Magento\Framework\App\Action\Context;

class Update extends \Magento\Framework\App\Action\Action
{
    protected $_postFactory;

    public function __construct(context $context, \Mageplaza\DevelopmentGuide\Model\PostFactory $postFactory
    )
    {
        $this->_postFactory = $postFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $post = $this->_postFactory->create();
        $up = [
            'name' => "updated............",
            'post_content' => "we will find out how to install and upgrade sql script for module in Magento 2",
            'url_key' => '/magento-2-module-development/magento-2-how-to-create-sql-setup-script.html-updated',
            'tags' => 'magento 2,mageplaza devdlopmentguide updated',
            'categories' => 'categories blog updated',
            'status' => 1
        ];
        $post->Load(5);

        try {
            if ($post->getData('post_id')) {
                $post->addData($up)->save();
                echo "updated successfully";
            } else {
                echo "update error message";
            }

        } catch (\Exception $e) {
            echo "Error updating";

        }
    }
}
