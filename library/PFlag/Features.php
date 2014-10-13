<?php
require_once dirname(__FILE__) . '/Config/Ini.php';
require_once dirname(__FILE__) . '/Config/Array.php';
require_once dirname(__FILE__) . '/Interface/Config.php';
require_once dirname(__FILE__) . '/Feature.php';
/**
 * Class of Feature Management
 * e.g.
 *     PFlag_Features::loadIniConf(CONF_PATH . '/features.ini');
 *     PFlag_Features::loadArrConf(CONF_PATH . '/features.php');
 *     if(PFlag_Features::get('UCVS_FAVORITE_DOUBLE11')->isActive()){
 *         // do something magic
 *     }
 * @author hejunhua
 *
 */
class PFlag_Features {

    // name=>feature
    protected static $featureMaps;
    
    protected static $objConfig;

    /**
     * load ini config file
     * @param string $iniFile
     * @param string $section sepcified section
     */
    public static function loadIniConf($iniFile, $section = null) { 
        self::setConfig(new PFlag_Config_Ini($iniFile, $section));
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
     * load instance of PFlag_Interface_Config
     * @param PFlag_Interface_Config $objConf
     */
    public static function setConfig(PFlag_Interface_Config $objConf) {
        self::$objConfig = $objConf;
    }

    /**
     * get instance of Feature
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
