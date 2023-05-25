<?php

namespace Mageplaza\DevelopmentGuide\Observer;

class ChangeDisplayText implements \Magento\Framework\Event\ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
//        echo $observer->getEvent()->getData('mp_text');
//        echo  $observer->getData('mpl');

//        $displayText = $observer->getData('mp_text');
//        $displayText->getMText();
        echo $observer->getEvent()->getMpText();
        $dataObject = $observer->getEvent()->getDataObject();
        echo "<pre>";
        var_dump($dataObject);
        echo "</pre>";
        echo $dataObject->getData('text');
        $dataObject->setTest('Mageplaza Helloworld');
        echo "<pre>";
        var_dump($dataObject);
        echo "</pre>";
//      echo $displayText->getText() . " - Event </br>";
//    $displayText->setText('Execute event successfully.');

        return $this;
    }
}
