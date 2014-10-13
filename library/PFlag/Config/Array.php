<?php
require_once dirname(__FILE__) . '/../Interface/Config.php';

class PFlag_Config_Array implements PFlag_Interface_Config {

    protected $arrConf;

    /**
     * construct
     * @param string $phpFile [description]
     * @return null 
     */
    public function __construct($phpFile = '') {
        if (file_exists($phpFile)) {
            require ($phpFile); // false using require_once if load twice
            $this->arrConf = $g_arrFeatureConfig;
        }
    }

    /**
     * set config
     * @param array $arrConfig
     * @return null
     */
    public function setConfig($arrConfig) {
        $this->arrConf = $arrConfig;
    }

    /**
     * 获取所有特征名的数值
     * @return array $arrFeatureNames
     */
    public function getFeatureNames() {
        $arrFeatureNames = array();
        if (isset($this->arrConf['features'])) {
            $arrFeatureNames = array_keys($this->arrConf['features']);
        }
        return $arrFeatureNames;
    }

    /**
     * 获取特征属性值的数值
     * @param  string $strFeatureName
     * @return array $arrAttr
     */
    public function getFeatureAttrs($strFeatureName) {
        $arrAttr = null;
        if (isset($this->arrConf['features'][$strFeatureName])) {
            $arrAttr         = $this->arrConf['features'][$strFeatureName];
            $arrAttr['name'] = $strFeatureName;
        }
        return $arrAttr;
    }
}
