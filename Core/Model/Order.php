<?php

/*
 * Non-persistent Order model for gathering observer data to be sent
 * to SiftScience API per-request.
 */

class SiftScience_Core_Model_Order extends Mage_Core_Model_Abstract
{
    /**
     * Event: sales_model_service_quote_submit_before
     *
     * This hook is called on the same request before a purchase is
     * attempted. Contains all information necessary for $create_order,
     * and most for $transaction.
     *
     * Since this is only and always called when a transaction is attempting,
     * this should dispatch $transaction to be sent.
     *
     * @param  Object $observer Event object
     * @return Object $observer
     */
    public function order_before_hook($observer)
    {
        $order = $observer->getEvent()->getOrder();

        // TODO: Refactor all this into a testable.
        $orderId = $order->getQuote()->getReservedOrderId();
        // TODO: This calculation is good for USD/Euro, but is it universal?
        $grandTotalInMicros = $order->getGrandTotal() * 100 * 10000;
        $currencyCode = $order->getQuote()->getQuoteCurrencyCode();
        $userEmail = $order->getCustomerEmail();

        $billingAddress = $order->getBillingAddress();
        $shippingAddress = $order->getShippingAddress();

        $items = $order->getAllVisibleItems();

        // /TODO: Refactor

        $this->setGrandTotalInMicros($grandTotalInMicros);
        $this->setCurrencyCode($currencyCode);
        $this->setOrderId($orderId);
        $this->setUserEmail($userEmail);
        $this->setBillingAddress($billingAddress);
        $this->setItems($items);

        if (!empty($shippingAddress)) {
            $this->setShippingAddress($shippingAddress);
        }

        return $observer;
    }
}
