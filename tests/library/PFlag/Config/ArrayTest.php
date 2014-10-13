<?php
require_once SRC_PATH . '/library/PFlag/Config/Array.php';

/**
 * @author hejunhua <hejunhua@baidu.com>
 * @since 2013-12-05
 */
class PFlag_Config_ArrayTest extends PHPUnit_Framework_TestCase {

    public function testConfig() {
        $objConf = new PFlag_Config_Array();
        $arrConfig = array(
            'features' => array(
                'UCVS_HOMEPAGE_V3' => array(
                    'type' => 'business','strategy' => 'Gradual'),
                'UCVS_HISTORY_SWITCH' => array(
                    'enabled' => false,'type' => 'release'),
                'UCVS_FAVORITE_DOUBLE11' => array(
                    'enabled' => true,'type' => 'business',
                    'strategy' => 'ReleaseDate',
                    'params' => array(
                        'start_date' => '2013-11-06 00:00:00',
                        'end_date' => '2013-11-11 23:59:59'
                    ),
                )
            )
        );
        $objConf->setConfig($arrConfig);
        $this->assertTrue(count($objConf->getFeatureNames()) === 3);
        $attrs = $objConf->getFeatureAttrs('UCVS_FAVORITE_DOUBLE11');
        $this->assertTrue($attrs['enabled']);
    }
}
