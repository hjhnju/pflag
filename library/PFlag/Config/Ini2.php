<?php
require_once dirname(__FILE__) . '/../Interface/Config.php';
/**
 * PFlag config from .ini file.
 * e.g.
 * [base]
 * feature.F1.enabled = false
 * feature.F1.type = business
 * feature.F1.params.start_date = '2013-11-06 00:00:00'
 * feature.F1.params.end_date = '2013-11-11 23:59:59'
 * [dev:base]
 * feature.F1.enabled = true
 *
 * get dev section, result will be:
 * array(
 *   'feature' => array(
 *      'F1' => array
 *          'enabled' => 1,
 *          'type' => 'business',
 *          'params' => array(
 *              'start_date' => '2013-11-06 00:00:00',
 *              'end_date' => '2013-11-11 23:59:59',
 *           )
 *       )
 *   )
 * )
 *
 * @author hejunhua<hejunhua@baidu.com>
 * @since 2013-12-15
 *
 */
class PFlag_Config_Ini implements PFlag_Interface_Config {

    protected $arrConf;

    const BASE_SECTION = 'base';
    const DEFAULT_SECTION  = 'product';
    const DEFAULT_PREFIX   = 'features';
    const USED_VERSION_KEY = 'features.version';
    const USED_SECTION_KEY = 'features.section';

    public function __construct($iniFile = '') {
        if (file_exists($iniFile)) {
            $this->arrConf = $this->parse($iniFile);
        }
        if(empty($this->arrConf)){
            throw new Exception("Read config file failed!");
        }
    }

    /**
     * parse ini file to get config
     * @param  string $iniFile
     * @return array  $arrConfig
     */
    protected function parse($iniFile) {
        $arrKv = parse_ini_file($iniFile, true);

        // parse all sections
        $arrSections = $this->_parseSections($arrKv);

        $baseSection = $arrSections[self::BASE_SECTION];

        // check version
        $strVersion = isset($baseSection[self::USED_VERSION_KEY]) ? $baseSection[self::USED_VERSION_KEY] : self::CURRENT_VERSION;
        if ($strVersion !== self::CURRENT_VERSION) {
            throw new Exception("PFlag version didn't match config file version");
        }
        // get specified section
        $strUsedSection = isset($baseSection[self::USED_SECTION_KEY]) ? $baseSection[self::USED_SECTION_KEY] : self::DEFAULT_SECTION;
        if(!isset($arrSections[$strUsedSection])){
            throw new Exception("Used section $strSelectSection not exits!");
        }

        $arrSection = $this->_getSection($arrSections, $strUsedSection);
        $arrConf = $this->_parseFields($arrSection);
        $arrConf['version'] = $strVersion;
        
        return $arrConf;
    }

    /**
     * process section extension, each section add a 'parent' field
     * e.g. [dev:product] as [dev] section
     * @param  array  $arrKv, result of parse_ini_file(file, true)
     * @return array  $arrSections, all sections
     */
    private function _parseSections($arrKv){
        $arrSections = array();
        foreach ($arrKv as $strSection => $arrSectionKv) {
            // section doesn't extends other section
            if (strpos($strSection, ':') === false) {
                $arrSections[$strSection] = $arrSectionKv;
                $arrSections[$strSection]['parent'] = null;
            } else {
                list($child, $parent) = explode(':', $strSection);
                if (!isset($arrSections[$parent])) {
                    throw new Exception("Section " . $parent . " not exists!");
                }
                $arrSections[$child] = $arrSectionKv;
                $arrSections[$child]['parent'] = $parent;
            }
        }
        return $arrSections;
    }

    /**
     * get specified section
     * @param  [type] $arrSections [description]
     * @param  [type] $section     [description]
     * @return [type]              [description]
     */
    private function _getSection($arrSections, $section){
        $sectionInfo = $arrSections[$section];
        while($parent = $arrSections[$section]['parent']) {
            foreach ($arrSections[$parent] as $key => $value) {
                if (!isset($sectionInfo[$key])) {
                    $sectionInfo[$key] = $value;
                }
            }
            $section = $parent;
        }
        return $sectionInfo;
    }

    /**
     * parse kv field to array
     * e.g 
     *     a.b.c = x
     *     a.b.d = y
     * to 
     *     array('a'=>array('b'=>array('c'=>'x', 'd'=>'y')))
     * @param  [type] $arrSectionFields [description]
     * @return [type]                   [description]
     */
    private function _parseFields($arrSectionFields) {
        $result = array();
        foreach ($arrSectionFields as $key => $value) {
            $newField = $this->_recursiveParseKey($key, $value);
            $result = self::_mergeArrayResult($result, $newField);
            
        }
        return $result;
    }

    private function _recursiveParseKey($key, $value){
        $pos = strpos($key, '.');
        if ($pos === false) {
            return array($key => $value);
        }
        $pre = substr($key, 0, $pos);
        $post = substr($key, $pos+1);
        $result = $this->_recursiveParseKey($post, $value);
        return array($pre=>$result);
    }

     /**
     * Merge two arrays recursively overwriting the keys in the first array
     * if such key already exists
     * @param mixed $a Left array to merge right array into
     * @param mixed $b Right array to merge over the left array
     * @return mixed
     */
    private static function _mergeArrayResult($a, $b)
    {
        // merge arrays if both variables are arrays
        if (is_array($a) && is_array($b)) {
            // loop through each right array's entry and merge it into $a
            foreach ($b as $key => $value) {
                if (isset($a[$key])) {
                    $a[$key] = self::_mergeArrayResult($a[$key], $value);
                } else {
                    if($key === 0) {
                        $a= array(0 => self::_mergeArrayResult($a, $value));
                    } else {
                        $a[$key] = $value;
                    }
                }
            }
        } else {
            // one of values is not an array
            $a = $b;
        }
 
        return $a;
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
