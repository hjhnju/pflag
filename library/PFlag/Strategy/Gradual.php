<?php
require_once dirname(dirname(__FILE__)) . '/Interface/Strategy.php';

/**
 * 灰度发布策略
 * 若有userid, userid按100取模，小于percent部分命中小流量
 * 无userid则随机
 * @author hejunhua<hejunhua@baidu.com>
 * @since 2013-12-20
 */
class PFlag_Strategy_Gradual implements PFlag_Interface_Strategy {

    const PARAM_PERCENT = 'percent';
    
    /**
     * 获取策略名
     * @return string $name
     */
    public function getName() {
        return 'Gradual';
    }

    /**
     * 判断策略是否生效
     * @param  PFlag_Interface_Feature $feature object of feature
     * @param  PFlag_Interface_User    $user    object of user
     * @return boolean
     */
    public function isActive(PFlag_Interface_Feature $feature, PFlag_Interface_User $user = null) {
        // check percent
        $floatPercent = floatval($feature->getParam(self::PARAM_PERCENT));
        $intPercent = intval($floatPercent * 100);
        $intUid = empty($user) ? null : $user->getUserid();
        
        if ($intPercent > 0 && $intPercent <= 100) {
            // check
            if (empty($intUid)) {
                $intRand = rand(1, 100);
            } else {
                $intRand = $intUid % 100;
            }
            if ($intRand <= $intPercent) {
                return true;
            }
        }
        return false;
    }
}
