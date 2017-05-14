<?php

/*
 * Non-persistent Payment model for gathering observer data to be sent
 * to SiftScience API per-request.
 */

class SiftScience_Core_Model_Payment extends Mage_Core_Model_Abstract
{
    /**
     * TODO: This method is very incomplete. Needs alternate payment methods.
     *
     * @param  [type] $observer [description]
     * @return [type]           [description]
     */
    public function payment_save_after_hook($observer)
    {
        $this->setPaymentType('$third_party_processor');

        return $observer;
    }

    public function getEventData()
    {
        $data = array();
        $paymentType = $this->getPaymentType();
        if (!empty($paymentType)) {
            $data['$payment_type'] = $paymentType;
        }

        return $data;
    }
}
