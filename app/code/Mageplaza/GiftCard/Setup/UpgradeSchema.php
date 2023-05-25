<?php

namespace Mageplaza\GiftCard\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        if (version_compare($context->getVersion(), '1.2.0', '<')) {
            if (!$installer->tableExists('giftcard_history')) {
                $table = $installer->getConnection()->newTable(
                    $installer->getTable('giftcard_history')
                )
                    ->addColumn(
                        'history_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        [
                            'identity' => true,
                            'nullable' => false,
                            'primary' => true,
                            'unsigned' => true,
                        ],
                        'History ID'
                    )
                    ->addColumn(
                        'giftcard_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        [
                            'nullable' => false,
                            'unsigned' => true,
                        ],
                        'Gift Card ID'
                    )
                    ->addColumn(
                        'customer_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        [
                            'nullable' => false,
                            'unsigned' => true,
                        ],
                        'Customer ID'
                    )
                    ->addColumn(
                        'amount',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        [],
                        'amount '
                    )
                    ->addColumn(
                        'action',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        [],
                        'action '
                    )
                    ->addColumn(
                        'action_time',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                        null,
                        ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                        'action_time At'
                    )
                    ->addColumn(
                        'created_at',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                        null,
                        ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                        'Created At'
                    )
                    ->addColumn(
                        'updated_at',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                        null,
                        ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
                        'Updated At')
                    ->addForeignKey(
                        $installer->getFkName('giftcard_history', 'giftcard_id', 'giftcard_code', 'giftcard_id'),
                        'giftcard_id',
                        $installer->getTable('giftcard_code'),
                        'giftcard_id',
                        \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                    )
                    ->addForeignKey(
                        $installer->getFkName('giftcard_history', 'customer_id', 'customer_entity', 'entity_id'),
                        'customer_id',
                        $installer->getTable('customer_entity'),
                        'entity_id',
                        \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                    )
                    ->setComment('GiftCard Hisstory Table');
                $installer->getConnection()->createTable($table);
                $installer->getConnection()->addIndex(
                    $installer->getTable('giftcard_history'),
                    $setup->getIdxName(
                        $installer->getTable('giftcard_history'),
                        ['action'],
                        \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                    ),
                    ['action'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                );
            }
        }
        if (version_compare($context->getVersion(), '2.0.0', '<')
            && !$installer->getConnection()->tableColumnExists('customer_entity', 'giftcard_balance')) {
            $installer->getConnection()->addColumn(
                $installer->getTable('customer_entity'),
                'giftcard_balance',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                    'nullable' => true,
                    'length' => '12,4',
                    'comment' => 'balance của customer',
                    'after' => 'email'
                ]
            );
        }
        if (version_compare($context->getVersion(), '2.0.0', '<')
            && !$installer->getConnection()->tableColumnExists('quote', 'giftcard_code')) {
            $installer->getConnection()->addColumn(
                $installer->getTable('quote'),
                'giftcard_code',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => false,
                    'length' => '255',
                    'comment' => 'Gifr Card Code',
                    'after' => 'coupon_code'
                ]
            );
        }
        $installer->endSetup();
    }
}
