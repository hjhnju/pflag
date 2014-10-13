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

    private $clientIp;

    public function __construct(){
        $this->clientIp = $this->getClientIp();
    }
    
    /**
     * 获取策略名
     * @return string $name
     */
    public function getName() {
        return 'ClientIp';
    }
    
    /**
     * 判断策略是否生效
     * @param  PFlag_Interface_Feature $feature object of feature
     * @param  PFlag_Interface_User    $user    object of user
     * @return boolean
     */
    public function isActive(PFlag_Interface_Feature $feature, PFlag_Interface_User $user = null) {
        $ipList = $feature->getParam(self::PARAM_WHITE_LIST);
        
        foreach ($ipList as $strIpExp) {
            $ipExp = str_replace('.', '\.', $strIpExp);
            $ipExp = str_replace('*', '.*', $ipExp);
            $ipExp = '/^' . $ipExp . '$/';
            if (preg_match($ipExp, $this->clientIp)) {
                return true;
            }
        }
        return false;
    }

    /**
     * get real client ip
     * @return string $ip
     */
    protected function getClientIp() {
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
