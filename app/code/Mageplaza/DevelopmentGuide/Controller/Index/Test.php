<?php

namespace Mageplaza\DevelopmentGuide\Controller\Index;

class Test extends \Magento\Framework\App\Action\Action
{

    public function execute()
    {
        $textDisplay = new \Magento\Framework\DataObject(array('text' => 'Mageplaza'));
        $this->_eventManager->dispatch('mageplaza_developmentguide_display_text',
            [
                'mp_text' => 'test Event',
                'mpl' => 'abc',
                'data_object' => $textDisplay
            ]);
        echo $textDisplay->getTest();
        exit;
    }
}
