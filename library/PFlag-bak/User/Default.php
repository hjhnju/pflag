<?php
require_once dirname(dirname(__FILE__)) . '/Interface/User.php';
class PFlag_User_Default implements PFlag_Interface_User {

    protected $attributes;
    protected $name;
    protected $userid;

    public function __construct($arrAttrs = array()) {
        $this->attributes = array();
        foreach ($arrAttrs as $key => $value) {
            $this->attributes[$key] = $value;
        }
        $this->name = 'default-user';
    }

    public function getAttribute($strName) {
        return isset($this->attributes[$strName]) ? $this->attributes[$strName] : null;
    }

    public function setAttribute($strName, $mixValue) {
        $this->attributes[$strName] = $mixValue;
    }

    public function getName() {
        return $this->name;
    }
    
    public function setName($name) {
        $this->name = $name;
    }
        
    public function getUserid(){
        return $this->userid;
    }
    
    public function setUserid($intUserid){
        $this->userid = $intUserid;
    }

    public function isAdmin() {
        return false;
    }
}