<?php
interface PFlag_Interface_Feature{

    const TYPE_RELEASE = 'release';
    
    const TYPE_BUSINESS = 'business';
    
    const GROUP_DEFAULT = 'default';
      
    /**
     * get feature name, which is uniq
     */
    public function getName();
    
    /**
     * check if feature is active
     */
    public function isActive();
    
    /**
     * get parameter by name
     * @param string $strParamName
     */
    public function getParam($strParamName);

}