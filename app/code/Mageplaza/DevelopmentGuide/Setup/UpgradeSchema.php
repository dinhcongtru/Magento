<?php

namespace Mageplaza\DevelopmentGuide\Setup;

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
            if (!$installer->tableExists('mageplaza_blog_category')) {
                $table = $installer->getConnection()->newTable(
                    $installer->getTable('mageplaza_blog_category')
                )
                    ->addColumn(
                        'category_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        [
                            'identity' => true,
                            'nullable' => false,
                            'primary' => true,
                            'unsigned' => true,
                        ],
                        'Category ID'
                    )
                    ->addColumn(
                        'name',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        ['nullable => false'],
                        'Category Name'
                    )
                    ->addColumn(
                        'description',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        [],
                        'Category description'
                    )
                    ->addColumn(
                        'image',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        [],
                        'Category Image'
                    )
                    ->addColumn(
                        'created_at',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                        null,
                        ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                        'Created At'
                    )->addColumn(
                        'updated_at',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                        null,
                        ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
                        'Updated At')
                    ->setComment('Post Table');
                $installer->getConnection()->createTable($table);

                $installer->getConnection()->addIndex(
                    $installer->getTable('mageplaza_blog_category'),
                    $setup->getIdxName(
                        $installer->getTable('mageplaza_blog_category'),
                        ['name', 'description', 'image'],
                        \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                    ),
                    ['name', 'description', 'image'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                );
            }
            if (!$installer->tableExists('mageplaza_developmentguide_post')) {
                $table = $installer->getConnection()->newTable(
                    $installer->getTable('mageplaza_developmentguide_post')
                )
                    ->addColumn(
                        'post_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        [
                            'identity' => true,
                            'nullable' => false,
                            'primary' => true,
                            'unsigned' => true,
                        ],
                        'Post ID'
                    )
                    ->addColumn(
                        'name',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        ['nullable => false'],
                        'Post Name'
                    )
                    ->addColumn(
                        'url_key',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        [],
                        'Post URL Key'
                    )
                    ->addColumn(
                        'post_content',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        '64k',
                        [],
                        'Post Post Content'
                    )
                    ->addColumn(
                        'tags',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        [],
                        'Post Tags'
                    )
                    ->addColumn(
                        'status',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        1,
                        [],
                        'Post Status'
                    )
                    ->addColumn(
                        'featured_image',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        [],
                        'Post Featured Image'
                    )
                    ->addColumn(
                        'created_at',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                        null,
                        ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                        'Created At'
                    )->addColumn(
                        'updated_at',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                        null,
                        ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
                        'Updated At')
                    ->setComment('Post Table');
                $installer->getConnection()->createTable($table);

                $installer->getConnection()->addIndex(
                    $installer->getTable('mageplaza_developmentguide_post'),
                    $setup->getIdxName(
                        $installer->getTable('mageplaza_developmentguide_post'),
                        ['name', 'url_key', 'post_content', 'tags', 'featured_image'],
                        \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                    ),
                    ['name', 'url_key', 'post_content', 'tags', 'featured_image'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                );
            }
        }

        if (version_compare($context->getVersion(), '1.2.1', '<')
            && !$installer->getConnection()->tableColumnExists('mageplaza_developmentguide_post', 'categories')) {
            $installer->getConnection()->addColumn(
                $installer->getTable('mageplaza_developmentguide_post'),
                'categories',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => true,
//					'length' => '255',
                    'comment' => 'categories',
                    'after' => 'status'
                ]
            );
        }


        $installer->endSetup();
    }
}
