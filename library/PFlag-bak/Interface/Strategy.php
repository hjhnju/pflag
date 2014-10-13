<?php
require_once dirname(__FILE__) . '/Feature.php';
require_once dirname(__FILE__) . '/User.php';

interface PFlag_Interface_Strategy {
    
    public function getName();
    /**
     * check if strategy is active 
     * @param PFlag_Interface_Feature $feature
     * @param PFlag_Interface_User $user
     * @return true | false
     */
    public function isActive(PFlag_Interface_Feature $feature, PFlag_Interface_User $user = null);
}