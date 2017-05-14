<?php

class SiftScience_Core_Block_Jssnippet extends Mage_Page_Block_Html
{
    public function getUserId()
    {
        return Mage::getSingleton('siftscience_core/session')->getEmail();
    }

    public function getSessionId()
    {
        return Mage::getSingleton('siftscience_core/session')->getSessionId();
    }
}