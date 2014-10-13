<?php
require_once dirname(__FILE__) . '/Interface/User.php';
require_once dirname(__FILE__) . '/User/Default.php';

class PFlag_UserProvider {
    protected static $objUser = null;
    
    /**
     * get current user instance
     * @return object | NULL
     */
    public static function getCurrentUser(){
        return self::$objUser;
    }
    
    /**
     * bind new user instance
     * @param PFlag_Interface_User $user
     */
    public static function bind(PFlag_Interface_User $user){
        self::$objUser = $user;
    }
    
    public static function release() {
        self::$objUser = null;
    }
}