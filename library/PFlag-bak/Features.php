<?php
require_once dirname(__FILE__) . '/Config/Ini.php';
require_once dirname(__FILE__) . '/Config/Array.php';
require_once dirname(__FILE__) . '/Interface/Config.php';
require_once dirname(__FILE__) . '/Feature.php';
/**
 * Class of Feature Management
 * e.g.
 *     PFlag_Features::loadIniConf(CONF_PATH . 'features.ini');
 *     PFlag_Features::loadArrConf(CONF_PATH . 'features.php'); 
 *     
 *     if(PFlag_Features::get('UCVS_FAVORITE_DOUBLE11')->isActive()){
 *         // do something magic
 *     }
 * @author hejunhua
 *
 */
class PFlag_Features {

    // name=>feature
    protected static $featureMaps;

    // version of feature management
    protected static $version;
    
    protected static $objConfig;

    /**
     * load ini config file
     * @param string $iniFile
     */
    public static function loadIniConf($iniFile) {
        self::setConfig(new PFlag_Config_Ini($iniFile));
    }

    /**
     * load php config file
     * @param string $phpFile
     */
    public static function loadArrConf($phpFile) {
        $objConf = new PFlag_Config_Array($phpFile);
        self::setConfig($objConf);
    }

    /**
     * set config object
     * @param PFlag_Interface_Config $objConf
     */
    public static function setConfig(PFlag_Interface_Config $objConf) {
        self::$version = $objConf->getVersion();
        self::$objConfig = $objConf;
    }

    /**
     * get Feature instance
     * @param string $strFeatureName
     * @throws Exception if feature not exists
     * @return PFlag_Feature
     */
    public static function get($strFeatureName) {
        if (!isset(self::$featureMaps[$strFeatureName])){
            $arrAttr = self::$objConfig->getFeatureAttrs($strFeatureName);
            if (!empty($arrAttr)){  
                //create Feature instance
                $objFeature = new PFlag_Feature($arrAttr);
                self::$featureMaps[$objFeature->getName()] = $objFeature;
            } else {
                throw new Exception('No Such Feature!');
            }
        }
        return self::$featureMaps[$strFeatureName];
    }
}
