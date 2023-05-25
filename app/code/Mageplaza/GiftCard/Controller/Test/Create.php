<?php

namespace Mageplaza\GiftCard\Controller\Test;

use Magento\Framework\App\Action\Context;

class Create extends \Magento\Framework\App\Action\Action
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
        $data = [
            'code' => "37acsjddmaldirndfa",
            'balance' => 12538335.3000,
            'amount_used' => 22538335.3000,
            'create_from' => 'magento 2,mageplaza giftcard',
        ];
        $code = [];
        $code[] = $post->getCollection()->getAllIds();
        if (in_array($data['code'], $code)) {
            echo 'code already exists';
        }
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
