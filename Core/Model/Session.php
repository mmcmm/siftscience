<?php

/*
 *
 * Non-persistent model to temporarily store data that will be sent to
 * SiftScience.
 */

class SiftScience_Core_Model_Session extends Mage_Core_Model_Abstract
{
    /**
     * Called before the layout is rendred. Gathers session information.
     * For testing, considered a controller.
     *
     * @param  Object $observer
     * @return Object $observer
     */
    public function front_init_hook($observer)
    {
        // Get the real session, set it to this model
        $siftSession = Mage::getSingleton('core/session', array('name' => 'siftscience'));
        $sessionId = $siftSession->getSiftSession();

        // TODO: This is testable. Refactor
        if (empty($sessionId)) {
            // getSessionId will generate a unique ID if session is truely empty
            $siftSession->setSiftSession($this->getSessionId());
        } else {
            $this->setSessionId($sessionId);
        }

        $email = null;
        // $user_id = null;
        if (Mage::getSingleton('customer/session')->isLoggedIn()) {
            $email = Mage::getSingleton('customer/session')->getCustomer()->getEmail();
            // $user_id = Mage::getSingleton('customer/session')->getCustomer()->getId();
        }
        $this->setEmail(strtolower($email));
        $this->setUserId(strtolower($email));
        return $observer;
    }

    /**
     * Returns stored session, or generates a new unique id, saving and
     * returning it.
     * @return string Session ID
     */
    public function getSessionId()
    {
        $session_id = $this->getData('session_id');

        if ($session_id === null) {
            $this->setData('session_id', uniqid());
            $session_id = $this->getData('session_id');
        }

        return $session_id;
    }

    /**
     * Debugging use only
     * @param  [type] $observer [description]
     * @return [type]           [description]
     */
    public function front_after_hook($observer)
    {
        // Mage::log( '[SIFT_DUMP] Post layout load.');
        // Mage::log( var_export($this, TRUE) );
    }
}