<?php

namespace Mageplaza\DevelopmentGuide\Model;
class Post extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'mageplaza_developmentguide_post';

    protected $_cacheTag = 'mageplaza_developmentguide_post';

    protected $_eventPrefix = 'mageplaza_developmentguide_post';

    protected function _construct()
    {
        $this->_init('Mageplaza\DevelopmentGuide\Model\ResourceModel\Post');
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
}
