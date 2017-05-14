<?php


abstract class SiftScience_Core_Model_Event_Abstract extends Mage_Core_Model_Abstract
{
    protected $_dispatched = false;
    protected $_apiKey;

    public function _construct()
    {
        parent::_construct();
        $this->_apiKey = Mage::getStoreConfig('siftscience_options/general/rest_api_key');
    }

    public function dispatch()
    {
        $dispatcher = Mage::getSingleton('siftscience_core/dispatcher');
        $this->_dispatched = $dispatcher->dispatch($this);
    }

    public function isDispatched()
    {
        return $this->_dispatched ? true : false;
    }

    abstract protected function getEventData();

    protected function baseData($siftSession)
    {
        $data = array();
        $data['$api_key'] = $this->_apiKey;
        $data['$type'] = $this->getEventType();
        if ($siftSession->getUserId()) {
            $data['$user_id'] = $siftSession->getUserId();
        }
        if ($siftSession->getSessionId()) {
            $data['$session_id'] = $siftSession->getSessionId();
        }
        return $data;
    }

    abstract protected function getEventType();

    protected function _parseAddress($address)
    {
        $siftAddress = array();

        $siftAddress['$name'] = $address->getFirstname() . ' ' . $address->getLastname();
        $siftAddress['$phone'] = $address->getTelephone();

        $streets = $address->getStreet();
        $siftAddress['$address_1'] = $streets[0];
        if (sizeof($streets) > 1) {
            $siftAddress['$address_2'] = $streets[1];
        }

        $siftAddress['$city'] = $address->getCity();
        $siftAddress['$region'] = $address->getRegion();
        $siftAddress['$country'] = $address->getCountry();
        $siftAddress['$zipcode'] = $address->getPostcode();

        return $siftAddress;
    }
}
