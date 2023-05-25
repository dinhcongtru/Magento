<?php

namespace Mageplaza\DevelopmentGuide\Controller\Index;

class Example extends \Magento\Framework\App\Action\Action
{

    protected $title;

    public function execute()
    {
        echo $this->setTitle('Mageplaza');
        echo $this->getTitle();
    }

    public function setTitle($title)
    {
        return $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }
}
