<?php

class SiftScience_Core_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function userId()
    {
        return Mage::getSingleton('siftscience_core/session')->getEmail();
    }

    public function sessionId()
    {
        return Mage::getSingleton('siftscience_core/session')->getSessionId();
    }

    public function getScore($sift_userId)
    {
        $sift_apiKey = Mage::getStoreConfig('siftscience_options/general/rest_api_key');

        $ctx = stream_context_create(
            array('http' =>
                array(
                    'timeout' => 2 // 2 seconds
                )
            )
        );
        $siftJson = file_get_contents("https://api.siftscience.com/v203/score/$sift_userId/?api_key=$sift_apiKey");
        $siftData = json_decode($siftJson, true);

        $score = null;
        if (array_key_exists('score', $siftData)) {
            $score = round($siftData['score'] * 100, 1);
        }

        return $score;
    }

    public function getScoreUrl($sift_userId)
    {
        return 'https://siftscience.com/console/users/' . urlencode($sift_userId);
    }
}
