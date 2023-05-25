<?php

namespace Mageplaza\GiftCard\Controller\Test;

use Magento\Framework\App\Action\Context;

class Update extends \Magento\Framework\App\Action\Action
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
        $up = [
            'code' => "update37acsjddmaldirndfa"
        ];
        $post->Load(1);
        try {
            if ($post->addData($up)->save()) {
                echo "updated successfully";
            } else {
                echo "update error message";
            }
        } catch (\Exception $e) {
            echo "Error updating";
        }
    }
}
