<?php
interface PFlag_Interface_Config{
    
    /**
     * Get all feature names as array
     * @return array $arrFeatureNames
     */
    public function getFeatureNames();
    
    /**
     * Get all attributes of a feature 
     * @param  string $strFeatureName
     * @return array $arrAttr
     */
    public function getFeatureAttrs($strFeatureName);
    
}
