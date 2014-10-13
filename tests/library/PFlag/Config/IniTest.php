<?php
require_once SRC_PATH . '/library/PFlag/Config/Ini.php';

/**
 * @author hejunhua <hejunhua@baidu.com>
 * @since 2013-12-05
 */
class PFlag_Config_IniTest extends PHPUnit_Framework_TestCase {

    public function testConfig() {
        $objConf = new PFlag_Config_Ini(CONF_PATH . '/feature.ini', 'dev');
        
        $feature = $objConf->getFeatureAttrs('MYFEATURE2');
        $this->assertTrue(empty($feature['enabled']) === false);
    }

}
