<?php
class Evisu_Contacts_Model_Observer
{
    public function lockAdminformsFields(Varien_Event_Observer $observer)
    {
        $observer->getEvent()->getBlock()->setLockedAttributes(array(
                'cu_name',
                'cu_store_id',
                'cu_subject',
                'cu_email',
                'cu_created_at',
                'cu_comment'
            )
        );
        return $this;
    }

}
