<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Customer admin controller
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */

//require_once 'Mage/Adminhtml/controllers/CustomerController.php';

class Grizzly_MassEmail_Adminhtml_MassEmailController extends Mage_Adminhtml_Controller_Action
{

    

    protected function _initCustomer($idFieldName = 'id')
    {
        $this->_title($this->__('Customers'))->_title($this->__('Manage Customers'));

        $customerId = (int) $this->getRequest()->getParam($idFieldName);
        $customer = Mage::getModel('customer/customer');

        if ($customerId) {
            $customer->load($customerId);
        }

        Mage::register('current_customer', $customer);
        return $this;
    }

    public function indexAction()
    {
        
        $this->loadLayout();
        $this->renderLayout();
    }

   
    public function sendAction()
    {
        
        $this->loadLayout();
        $this->renderLayout();
        $sendername = Mage::getStoreConfig('grizzly_massemail/general/email_sender',Mage::app()->getStore());
        $emailsubject = Mage::getStoreConfig('grizzly_massemail/general/email_subject',Mage::app()->getStore());

        $senderEmail = Mage::getStoreConfig('trans_email/ident_support/email');
        $templateId = Mage::getStoreConfig('grizzly_massemail/general/email_template',Mage::app()->getStore());
        $customersIds = $this->getRequest()->getParam('customer');
        $storeId = Mage::app()->getStore()->getId();
        if(!is_array($customersIds)) 
        {
             Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select customer(s).'));
        }

        else
        {

            foreach ($customersIds as $customerId) 
                {

                    
                    $customer = Mage::getModel('customer/customer')->load($customerId);
                    $customeremail = $customer->getData('email');
                    $customername = $customer->getData('firstname');
                    $emailTemplateVariables = array();
                    $emailTemplateVariables['customer'] = $customer;
                    $translate  = Mage::getSingleton('core/translate');
                    $sender = array('name' => $sendername,
                    'email' => $senderEmail);
                    
                    try
                    {         
                        Mage::getModel('core/email_template')
			            ->sendTransactional($templateId, $sender, $customeremail, $sendername, $emailTemplateVariables, $storeId);

			        	$translate->setTranslateInline(true);

                    }
                    catch(Exception $e)
                    {
                                
                        Mage::getSingleton('core/session')->addError($e->getMessage());
                        $this->_redirect('*/customer/index');
                        return false;
                    }

                
                
                }  

            $this->_redirect('*/customer/index');
            Mage::getSingleton('core/session')->addSuccess('The emails have been sent successfully.');   
                    
              
        
        

        }

    } 
}


    
    

?>