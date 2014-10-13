<?php
require_once dirname(__FILE__) . '/../Interface/Config.php';

class PFlag_Config_Array implements PFlag_Interface_Config {

    protected $arrConf;

    public function __construct($phpFile = '') {
        if (file_exists($phpFile)) {
            require ($phpFile); // false using require_once if load twice
            $this->arrConf = $g_arrFeatureConfig;
        }
    }

    public function setConfig($arrConfig) {
        $this->arrConf = $arrConfig;
    }

    public function getVersion() {
        return isset($this->arrConf['version']) ? $this->arrConf['version'] : null;
    }

    public function getFeatureNames() {
        $arrFeatureNames = array();
        if (isset($this->arrConf['features'])) {
            $arrFeatureNames = array_keys($this->arrConf['features']);
        }
        return $arrFeatureNames;
    }

    public function getFeatureAttrs($strFeatureName) {
        $arrAttr = null;
        if (isset($this->arrConf['features'][$strFeatureName])) {
            $arrAttr = $this->arrConf['features'][$strFeatureName];
            $arrAttr['name'] = $strFeatureName;
        }
        return $arrAttr;
    }
}
