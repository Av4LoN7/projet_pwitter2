<?php

/**
 * abstract class return pdo instance
 * Class coreModel
 */
abstract class coreModel
{
    protected $_pdo;

    // return pdo instance
    public function __construct()
    {
        $this->_pdo = SPDO::getInstance()->getPDO();
    }
}
?>