<?php
interface PFlag_Interface_User{
    
    /**
     * 获取用户的某个属性取值
     * @param  string $strName 属性名称
     * @return mix $mixValue 属性取值
     */
    public function getAttribute($strName);
    
    /**
     * 获取用户名
     * @return string $strUsername
     */
    public function getName();
    
    /**
     * 获取用户id
     * @return int $intUserid
     */
    public function getUserid();
    
    /**
     * 判断是否管理员
     * @return boolean
     */
    public function isAdmin();
    
}