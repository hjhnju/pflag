<?php
require_once dirname(dirname(__FILE__)) . '/Interface/Strategy.php';

class PFlag_Strategy_ReleaseDate implements PFlag_Interface_Strategy {
    
    /**
     * 获取策略名
     * @return string $name
     */
    public function getName() {
        return 'ReleaseDate';
    }

    /**
     * 判断策略是否生效
     * @param  PFlag_Interface_Feature $feature object of feature
     * @param  PFlag_Interface_User    $user    object of user
     * @return boolean
     */
    public function isActive(PFlag_Interface_Feature $feature, PFlag_Interface_User $user = null) {
        // get parameter
        $strStart = $feature->getParam('start_date');
        $intStartTs = strtotime($strStart);
        $strEnd = $feature->getParam('end_date');
        $intEndTs = strtotime($strEnd);
        
        $intCurrentTs = time();
        
        if ($intCurrentTs >= $intStartTs && $intCurrentTs < $intEndTs) {
            return true;
        }
        
        return false;
    }
}
