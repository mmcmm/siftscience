<?php

/*
 * Extending Model_Abstract is not necessary, but helpful for testing.
 */

class SiftScience_Core_Model_Observer extends Mage_Core_Model_Abstract
{
    public function front_after_hook($observer)
    {
        $dispatcher = Mage::getSingleton('siftscience_core/dispatcher');
        $dispatcher->fireAll();
        return $observer;
    }

    /**
     * Triggers the SiftScience Event_CreateOrder model to fire.
     * @param  array $observer array( 'order'=>$order, 'quote'=>$quote )
     * @return array $observer
     */
    public function order_success_hook($observer)
    {
        $order = $observer->getEvent()->getOrder();

        $user_id = Mage::getSingleton('siftscience_core/session')->getUserId();
        if (empty($user_id)) {
            // we need to overwrite this because we won't allow guest and it doesn't work with guests
            $user_id = Mage::helper('evofrd')->getUserEmail($order);
            Mage::getSingleton('siftscience_core/session')->setUserId($user_id);
        }

        $order->setSiftscience_userid($user_id);

        // Fire event after updating user id
        Mage::log('[SIFT] order_success_hook firing');
        Mage::getSingleton('siftscience_core/event_createOrder')->dispatch();

        //we need to fire all events so we get the most recent score and the account created
        Mage::getSingleton('siftscience_core/dispatcher')->fireAll();

        $order->save();

        return $observer;
    }

    /**
     * Triggers the SiftScience Event_CreateOrder model to fire.
     * @param  array $observer array( 'order'=>$order, 'quote'=>$quote )
     * @return array $observer
     */
    public function order_paid_hook($observer)
    {
        $order = $observer->getInvoice()->getOrder();

        Mage::log('[SIFT] order_paid_hook firing');
        $event_transaction = Mage::getSingleton('siftscience_core/event_transaction');
        $event_transaction->setOrder($order);
        $event_transaction->dispatch();

        //we need to fire all events so we get the most recent score and the account created
        Mage::getSingleton('siftscience_core/dispatcher')->fireAll();

        $order->save();

        return $observer;
    }

    public function add_product_hook($observer)
    {
        $atc = Mage::getSingleton('siftscience_core/event_addItemToCart');
        $atc->setProduct($observer->getEvent()->getProduct());
        $atc->dispatch();
    }
}
