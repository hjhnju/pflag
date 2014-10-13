<?php
require_once SRC_PATH . '/library/PFlag/Features.php';

/**
 * @author hejunhua <hejunhua@baidu.com>
 * @since 2013-12-05
 */
class PFlag_FeaturesTest extends PHPUnit_Framework_TestCase {

    public function testDefaultFeature() {
        PFlag_Features::loadArrConf(CONF_PATH . '/feature.php');
        $feature = PFlag_Features::get('MYFEATURE1');
        $this->assertTrue($feature->isActive() === false);
    }

    public function testIpStrategy() {
        PFlag_Features::loadArrConf(CONF_PATH . '/feature.php');
        //mock ip
        $mock = $this->getMock('PFlag_Strategy_ClientIp', array('getClientIp'));
        $mock->expects($this->any())
            ->method('getClientIp')
            ->will($this->returnValue('172.10.10.10'));

        $feature = PFlag_Features::get('MYFEATURE3');
        $feature->setStrategy($mock);
        //TODO:$this->assertTrue($mock->isActive($feature) === true);
        $this->assertTrue($feature->isActive() === true);
    }

    public function testGradualStrategy() {
        PFlag_Features::loadArrConf(CONF_PATH . '/feature.php');
        // 1.bind user
        $user = new PFlag_User_Default();
        $user->setUserid(12300);
        PFlag_UserProvider::bind($user);
        // 2.check feature
        $feature = PFlag_Features::get('MYFEATURE2');
        
        $this->assertTrue($feature->isActive() === true);
        
        $user->setUserid(12306);
        $this->assertTrue($feature->isActive() === false);
        
        PFlag_UserProvider::release();
        $cnt = 0;
        for($i = 0; $i < 1000; $i++) {
            $cnt = $feature->isActive() ? $cnt + 1 : $cnt;
        }
        $this->assertTrue($cnt > 0 && $cnt < 1000);
    }

    public function testDefaultFeatureByIni() {
        PFlag_Features::loadIniConf(CONF_PATH . '/feature.ini');
        $feature = PFlag_Features::get('MYFEATURE4');
        
        $this->assertTrue($feature->isActive() === false);
        
    }

    public function testGradualStrategyByIni() {
        PFlag_Features::loadIniConf(CONF_PATH . '/feature.ini', 'dev');
        // 1.bind user
        $user = new PFlag_User_Default();
        $user->setUserid(12300);
        PFlag_UserProvider::bind($user);
        // 2.check feature
        $feature = PFlag_Features::get('MYFEATURE2');
        
        $this->assertTrue($feature->isActive() === true);
        
        $user->setUserid(12311);
        $this->assertTrue($feature->isActive() === false);
        
        PFlag_UserProvider::release();
        $cnt = 0;
        for($i = 0; $i < 1000; $i++) {
            $cnt = $feature->isActive() ? $cnt + 1 : $cnt;
        }
        $this->assertTrue($cnt > 0 && $cnt < 1000);
    }

}
