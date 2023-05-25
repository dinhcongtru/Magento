<?php

namespace Mageplaza\DevelopmentGuide\Model;

use Magento\Framework\App\ResourceConnection;

class Cate extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'mageplaza_blog_category';
    protected $_resource;

    protected $_cacheTag = 'mageplaza_blog_category';

    protected $_eventPrefix = 'mageplaza_blog_category';

    protected function _construct()
    {
        $this->_init('Mageplaza\DevelopmentGuide\Model\ResourceModel\Cate');
    }

    public function __construct(\Magento\Framework\Model\Context $context,
                                \Magento\Framework\Registry      $registry,
                                ResourceConnection               $resource)
    {
        $this->_resource = $resource;
        parent::__construct($context, $registry);
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getDefaultValues()
    {
        $values = [];

        return $values;
    }

    public function updateCategory($cateId, $getNewId)
    {
        $connection = $this->_resource->getConnection();
        $table = $this->_resource->getTableName('mageplaza_blog_category'); // Table name of current model
//        $bind = $data;
//        $where = ['category_id =?' => $id];
//        $connection->update($table, $bind, $where);
        $sql = "UPDATE {$table} SET `category_id` = '{$cateId}' WHERE `mageplaza_blog_category`.`category_id` = $getNewId";
        $connection->query($sql);

    }
}
