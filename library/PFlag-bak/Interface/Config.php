<?php
interface PFlag_Interface_Config{

	const CURRENT_VERSION = '1.0';
    
    public function getVersion();
    
    public function getFeatureNames();
    
    public function getFeatureAttrs($strFeatureName);
    
}
