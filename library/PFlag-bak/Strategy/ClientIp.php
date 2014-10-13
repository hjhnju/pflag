<?php
require_once dirname(dirname(__FILE__)) . '/Interface/Strategy.php';

/**
 * 客户端Ip过滤策略
 * 配置文件支持白名单ip, 简单的正则匹配
 * @author hejunhua<hejunhua@baidu.com>
 * @since 2013-12-20
 */
class PFlag_Strategy_ClientIp implements PFlag_Interface_Strategy {

    const PARAM_WHITE_LIST = 'white_list';
    
    public function getName() {
        return 'ClientIp';
    }

    public function isActive(PFlag_Interface_Feature $feature, PFlag_Interface_User $user = null) {
        $ipList = $feature->getParam(self::PARAM_WHITE_LIST);
        
        foreach ($ipList as $strIpExp) {
            $ipExp = str_replace('.', '\.', $strIpExp);
            $ipExp = str_replace('*', '.*', $ipExp);
            $ipExp = '/^' . $ipExp . '$/';
            if (preg_match($ipExp, $this->_getClientIp())) {
                return true;
            }
        }
        return false;
    }

    /**
     * 获取真实客户端Ip
     */
    private static function _getClientIp() {
        if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) {
            $ip = getenv("HTTP_CLIENT_IP");
        } else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) {
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        } else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) {
            $ip = getenv("REMOTE_ADDR");
        } else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip = "unknown";
        }
        return $ip;
    }
}