<?php

namespace Mageplaza\DevelopmentGuide\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class UpgradeData implements UpgradeDataInterface
{
    protected $_postFactory;

    public function __construct(\Mageplaza\DevelopmentGuide\Model\PostFactory $postFactory)
    {
        $this->_postFactory = $postFactory;
    }

    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '1.2.0', '<')) {
            $data = [
                'code' => "3cfnbwedakavachers",
                'balance' => 72538335.3000,
                'amount_used' => 1212312.3000,
                'create_from' => 'magento 2,mageplaza GiftCard'
            ];
            $post = $this->_postFactory->create();
            $post->addData($data)->save();
        }
    }
}
