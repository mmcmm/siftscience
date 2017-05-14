<?php

/*
 * Modifies the flat order table to add a column for siftscience_userid, so that we
 * may retrieve it via Magento's order attribute.
 */
$this->startSetup();
$this->addAttribute('order', 'siftscience_userid', array(
    'type' => 'varchar',
    'label' => 'SiftScience UserID',
    'visible' => false,
    'required' => false,
    'visible_on_front' => false,
    'user_defined' => false
));

$this->endSetup();