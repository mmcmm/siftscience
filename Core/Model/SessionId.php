<?php

/**
 * Simple model to generate a unique id for SiftScience named session scope.
 *
 * TODO: DEPRECATED. Refactoring controller-specific logic
 * This needs to be moved to a per-request observer, check for
 * ('siftscience_core/session')->getSessionId(), and set if empty.
 * Allows for better test coverage.
 */
class SiftScience_Core_Model_SessionId extends Varien_Object
{
    public function getSessionId()
    {
        /*
          We need to maintain a session ID for guests that is
          persisted when a guest decides to log in before checking out.
          SiftScience will properly track a user when their _userId changes
          from guest (something like 'guest_SID54321') to a real ID
          ('danschuman@gmail.com'), so long as the sessionId is the same
          throughout navigation.

          Magento's core/session with a named scope seems to work properly--it
          persists after guest->user login transition, and is destroyed when
          a user logs out.
        */

        $siftSession = Mage::getSingleton('core/session', array('name' => 'siftscience'));

        $sessionId = $siftSession->getSiftSession();

        if (empty($sessionId)) {
            $sessionId = uniqid();
            $siftSession->setSiftSession($sessionId);
        }

        return $sessionId;
    }
}