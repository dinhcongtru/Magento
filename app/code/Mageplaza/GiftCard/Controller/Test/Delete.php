<?php

namespace Mageplaza\GiftCard\Controller\Test;

use Magento\Framework\App\Action\Context;

class Delete extends \Magento\Framework\App\Action\Action
{
    protected $_giftcardFactory;

    public function __construct(context $context, \Mageplaza\GiftCard\Model\GiftCardFactory $giftcardFactory)
    {
        $this->_giftcardFactory = $giftcardFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $post = $this->_giftcardFactory->create();
        $post->Load(3);
        try {
            if ($post->getId()) {
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
