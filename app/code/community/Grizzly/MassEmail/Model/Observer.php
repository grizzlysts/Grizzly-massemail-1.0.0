<?php
class Grizzly_Massemail_Model_Observer
{
    public function addMassAction($observer)
    {
        $block = $observer->getEvent()->getBlock();
        $enable_module = Mage::getStoreConfig('grizzly_massemail/general/enable_module',Mage::app()->getStore());
        if($enable_module =="1")

        { 
            if(get_class($block) =='Mage_Adminhtml_Block_Widget_Grid_Massaction'
                && $block->getRequest()->getControllerName() == 'customer')
            {
                $block->addItem('massemail', array(
                    'label' => 'Send Mail',
                    'url' => Mage::app()->getStore()->getUrl('*/massEmail/send'),
                ));
            }
        }    

    }
}

?>