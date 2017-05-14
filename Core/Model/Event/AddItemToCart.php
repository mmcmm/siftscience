<?php

class SiftScience_Core_Model_Event_AddItemToCart extends SiftScience_Core_Model_Event_Abstract
{
    protected $_eventType = '$add_item_to_cart';

    public function getEventType()
    {
        return $this->_eventType;
    }

    public function getEventData()
    {
        $siftSession = Mage::getSingleton('siftscience_core/session');
        $data = $this->baseData($siftSession);
        $p = $this->getProduct();

        $item = $this->_buildItem($p);
        $data['$item'] = $item;
        return $data;
    }

    private function _buildItem($p)
    {
        $item = array();
        $this->_addIfPresent($item, '$item_id', $p->getId());
        $this->_addIfPresent($item, '$product_title', $p->getName());
        $this->_addIfPresent($item, '$sku', $p->getSku());
        $this->_addIfPresent($item, '$manufacturer', $p->getAttributeText('manufacturer'));
        $this->_addIfPresent($item, '$color', $p->getAttributeText('color'));
        $this->_addIfPresent($item, '$quantity', $p->getQty());

        $price = $p->getPrice();
        if (!empty($price)) {
            $item['$price'] = $price * 100 * 10000;
            $item['$currency_code'] = Mage::app()->getStore()->getCurrentCurrencyCode();
        }
        return $item;
    }

    private function _addIfPresent(&$arr, $key, $val)
    {
        if (!empty($val)) {
            $arr[$key] = $val;
        }
    }
}
