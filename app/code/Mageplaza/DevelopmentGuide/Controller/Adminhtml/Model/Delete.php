<?php

namespace Mageplaza\DevelopmentGuide\Controller\Adminhtml\Model;

use Magento\Framework\App\Action\Context;

class Delete extends \Magento\Framework\App\Action\Action
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

        try {
            $post->Load(9);
            if ($post->getData('post_id')) {
                $post->delete();
                echo "Deleted successfully";
            } else {
                echo "No post to delete";
            }

        } catch (\Exception $e) {
            echo "Error deleting";

        }
    }
}
