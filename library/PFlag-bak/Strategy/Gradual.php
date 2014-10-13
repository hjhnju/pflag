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

    public function getName() {
        return 'Gradual';
    }

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
