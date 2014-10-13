<?php
require_once dirname(dirname(__FILE__)) . '/Interface/User.php';
class PFlag_User_Default implements PFlag_Interface_User {

    protected $attributes;
    protected $name;
    protected $userid;

    /**
     * construct
     * @param array $arrAttrs 属性数值
     */
    public function __construct($arrAttrs = array()) {
        $this->attributes = array();
        foreach ($arrAttrs as $key => $value) {
            $this->attributes[$key] = $value;
        }
        $this->name = 'default-user';
    }

    /**
     * 获取用户的某个属性取值
     * @param  string $strName 属性名称
     * @return mix $mixValue 属性取值
     */
    public function getAttribute($strName) {
        return isset($this->attributes[$strName]) ? $this->attributes[$strName] : null;
    }

    /**
     * 设置属性值
     * @param string $strName  属性名
     * @param mix $mixValue 属性值
     */
    public function setAttribute($strName, $mixValue) {
        $this->attributes[$strName] = $mixValue;
    }

    /**
     * 获取用户名
     * @return string $strUsername
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * 设置用户名
     * @param string $name 用户名
     */
    public function setName($name) {
        $this->name = $name;
    }
    
    /**
     * 获取用户id
     * @return string $strUid
     */ 
    public function getUserid(){
        return $this->userid;
    }
    
    /**
     * 设置用户id
     * @param string $intUserid 用户id
     */
    public function setUserid($intUserid){
        $this->userid = $intUserid;
    }
    
    /**
     * 判断是否管理员
     * @return boolean
     */
    public function isAdmin() {
        return false;
    }
}