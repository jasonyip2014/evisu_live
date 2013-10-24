<?php
class Evisu_AlertOOS_AlertController extends Mage_Core_Controller_Front_Action
{
    const ENTITY_TYPE = 'alertoos';

    public function postAction()
    {
        $post = $this->getRequest()->getPost();
        if ($post) {
            //$translate = Mage::getSingleton('core/translate');
            /* @var $translate Mage_Core_Model_Translate */
            //$translate->setTranslateInline(false);


            $postObject = new Varien_Object();
            $postObject->setData($post);

            $error = false;

            if (!Zend_Validate::is(trim($post['email_address']), 'EmailAddress')) {
                $error = true;
            }

            if ($error) {
                throw new Exception();
            }

            $model = Mage::getModel('adminforms/block',array('entity_type'=>'alertoos'));
            $model->setStoreId(0);

            $model->setAttributeSetId(Mage::getSingleton('eav/config')->getEntityType('alertoos')->getDefaultAttributeSetId());
            $model->setStore(Mage::app()->getStore()->getCode())
                ->setSku($post['sku'])
                ->setFirstName($post['first_name'])
                ->setLastName($post['last_name'])
                ->setEmailAddress($post['email_address'])
                ->setTelephone($post['telephone'])
                ->setCreatedAt(Mage::getModel('core/date')->date('Y-m-d H:i:s'))
                ->save();

            //create alert notification
            $customerId = null;
            if(Mage::getSingleton('customer/session')->isLoggedIn())
            {
                $customerId = Mage::getSingleton('customer/session')->getId();
            }
            else
            {
                if(!($customerId = $this->getCustomerIdByEmail($post['email_address'])))
                {
                    $customerId = $this->getCustomerIdByCreateAccount(array(
                        'first_name' => $post['first_name'],
                        'last_name' => $post['last_name'],
                        'email_address' => $post['email_address']
                    ));
                }
            }
            try {

                $model = Mage::getModel('productalert/stock')
                    ->setCustomerId($customerId)
                    ->setProductId($post['product_id'])
                    ->setWebsiteId(Mage::app()->getStore()->getWebsiteId());
                $model->save();
            }
            catch (Exception $e) {
                echo $this->__('Unable to update the alert subscription.');
            }

            $message = Mage::helper('evisu_alertoos')->__('Alert Added');
            echo $message;
            return;

        } else {
            $message = Mage::helper('evisu_alertoos')->__('No data for send. Please, try again later');
            echo $message;
            return;
        }
    }

    private function getCustomerIdByEmail($email)
    {
        $customer = Mage::getModel('customer/customer');

        $collection = $customer->getCollection()
            ->addFieldToFilter('email', $email)
            ->setPageSize(1);

        if($customer->getSharingConfig()->isWebsiteScope()) {
            $collection->addAttributeToFilter(
                'website_id',
                Mage::app()->getWebsite()->getId()
            );
        }
        if($collection->getSize())
        {
            return $collection->getFirstItem()->getId();
        }
        return null;
    }

    private function getCustomerIdByCreateAccount(array $userInfo)
    {
        $customer = Mage::getModel('customer/customer');

        $customer->setEmail($userInfo['email_address'])
            ->setFirstname($userInfo['first_name'])
            ->setLastname($userInfo['last_name'])
            ->setPassword($customer->generatePassword(10))
            ->setPrefix('')
            ->save();


        $customer->sendNewAccountEmail();
        return $customer->getId();
        //Mage::getSingleton('customer/session')->setCustomerAsLoggedIn($customer);

    }
}
