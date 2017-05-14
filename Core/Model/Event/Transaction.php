<?php


class SiftScience_Core_Model_Event_Transaction extends SiftScience_Core_Model_Event_Abstract
{

    protected $_eventType = '$transaction';
    protected $_order;

    public function getEventType()
    {
        return $this->_eventType;
    }

    public function setOrder($order)
    {
        $this->_order = $order;
    }

    public function getEventData()
    {
        $siftSession = Mage::getSingleton('siftscience_core/session');

        $data = $this->baseData($siftSession);
        // we need to overwrite this since are pulled from sesion.
        $data['$user_id'] = Mage::helper('evofrd')->getUserEmail($this->_order);
        unset($data['$session_id']);

        $data['$transaction_type'] = '$sale';
        $data['$transaction_status'] = '$success';
        $data['$amount'] = $this->_order->getGrandTotal() * 100 * 10000;
        $data['$currency_code'] = $this->_order->getOrderCurrencyCode();

        $data['$billing_address'] = $this->_parseAddress($this->_order->getBillingAddress());
        $shippingAddress = $this->_order->getShippingAddress();
        if (!empty($shippingAddress)) {
            $data['$shipping_address'] = $this->_parseAddress($shippingAddress);
        }

        $data['$order_id'] = $this->_order->getIncrementId();
        $data['$payment_method'] = ['$payment_type' => '$third_party_processor'];

        return $data;
    }
}
