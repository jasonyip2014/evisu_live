<?php

class Evisu_Contacts_IndexController extends Mage_Core_Controller_Front_Action
{

    const XML_PATH_EMAIL_RECIPIENT  = 'evisu_contacts/email/recipient_email';
    const XML_PATH_EMAIL_SENDER     = 'evisu_contacts/email/sender_email_identity';
    const XML_PATH_EMAIL_TEMPLATE   = 'evisu_contacts/email/email_template';
    const XML_PATH_ENABLED          = 'evisu_contacts/contacts/enabled';

    public function preDispatch()
    {
        parent::preDispatch();

        if( !Mage::getStoreConfigFlag(self::XML_PATH_ENABLED) ) {
            $this->norouteAction();
        }
    }

    public function indexAction()
    {
        $this->loadLayout();
        $formKey = md5(time().'evisu_contactus');
        Mage::getSingleton('customer/session')->setData('contact_us_form_key', $formKey);
        $this->getLayout()->getBlock('contact_form')
            ->setFormAction( Mage::getUrl('*/*/post') )
            ->setFormKey($formKey);

        //$this->_initLayoutMessages('customer/session');
        //$this->_initLayoutMessages('catalog/session');
        $this->renderLayout();
    }

    public function postAction()
    {
        $post = $this->getRequest()->getPost();
        if ( $post ) {
            //$translate = Mage::getSingleton('core/translate');
            /* @var $translate Mage_Core_Model_Translate */
            //$translate->setTranslateInline(false);
            try {

                $postObject = new Varien_Object();
                $postObject->setData($post);

                $error = false;

                if (!Zend_Validate::is(trim($post['name']) , 'NotEmpty')) {
                    $error = true;
                }
                echo $error;
                if (!Zend_Validate::is(trim($post['subject']) , 'NotEmpty')) {
                    $error = true;
                }

                if (!Zend_Validate::is(trim($post['email']), 'EmailAddress')) {
                    $error = true;
                }

                if ($post['formkey'] == Mage::registry('contact_us_form_key')) {
                    $error = true;
                }
                //Mage::getSingleton('customer/session')->unsetData('contact_us_form_key');
                if ($error) {
                    throw new Exception();
                }
                $mailTemplate = Mage::getModel('core/email_template');
                /* @var $mailTemplate Mage_Core_Model_Email_Template */
                $mailTemplate->setDesignConfig(array('area' => 'frontend'))
                    ->setReplyTo($post['email'])
                    ->sendTransactional(
                        Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE),
                        Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER),
                        Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT),
                        null,
                        array('data' => $postObject)
                    );

                if (!$mailTemplate->getSentSuccess()) {
                    throw new Exception();
                    Mage::log('Error. Email not send!',null,'evisu_contact_us.log');
                }

                $entityType = 'contact_us_grid';
                $model = Mage::getModel('adminforms/block', array('entity_type' => $entityType))
                    ->setStoreId(0)
                    ->setPageId($this->getEntityPageId())
                    ->setAttributeSetId($this->_getDefaultAttributeSetId($entityType));

                $model->setCuName($post['name'])
                    ->setCuEmail($post['email'])
                    ->setCuSubject($post['subject'])
                    ->setCuMessage($post['comment'])
                    ->setCuCreatedAt(date('Y-m-d H:m:s'))
                    ->setCuStoreId(Mage::app()->getStore()->getId())
                    ->save();

                $message = Mage::helper('contacts')->__('Thank you for your message. We will be in touch soon');
                echo $message;
                return;
            } catch (Exception $e) {
                //$translate->setTranslateInline(true);
                $message = Mage::helper('evisu_contacts')->__('Unable to submit your request. Please, try again later');
                echo $message;
                return;
            }

        } else {
            $message = Mage::helper('evisu_contacts')->__('No data for send. Please, try again later');
            echo $message;
            return;
        }
    }

    protected function getEntityPageId()
    {
        $cmsAdvancedPage = Mage::getResourceModel('cmsadvanced/page_collection')
            ->addAttributeToSelect('*')
            ->addFieldToFilter('page_type', 'contact_us');
        return $cmsAdvancedPage->getFirstItem()->getId();
    }

    protected function _getDefaultAttributeSetId($entityType)
    {
        return Mage::getSingleton('eav/config')->getEntityType($entityType)->getDefaultAttributeSetId();
    }

}
