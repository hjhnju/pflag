<?php
require_once dirname(__FILE__) . '/Feature.php';
require_once dirname(__FILE__) . '/User.php';
interface PFlag_Interface_Strategy {
    
    /**
     * 获取策略名
     * @return string $name
     */
    public function getName();

    /**
     * 判断策略是否生效
     * @param  PFlag_Interface_Feature $feature object of feature
     * @param  PFlag_Interface_User    $user    object of user
     * @return boolean
     */
    public function isActive(PFlag_Interface_Feature $feature, PFlag_Interface_User $user);
}