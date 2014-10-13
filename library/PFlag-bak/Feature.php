<?php
require_once dirname(__FILE__) . '/Interface/Feature.php';
require_once dirname(__FILE__) . '/Interface/Strategy.php';
require_once dirname(__FILE__) . '/UserProvider.php';

class PFlag_Feature implements PFlag_Interface_Feature {

    /**
     * name of feature, uniq id
     * @var string
     */
    protected $name;

    /**
     * true if feature is open 
     * @var boolean 
     * default:false
     */
    protected $enabled;

    /**
     * true if feature is visible
     * @var boolean
     */
    protected $active;

    /**
     * PFlag_Interface_Strategy
     * @var object 
     */
    protected $strategy;

    /**
     * const feature type
     * @var string
     */
    protected $type;

    /**
     * feature group tag
     * @var string
     */
    protected $group;
    
    /**
     * extra params
     * @var array
     */
    protected $params;

    /**
     * @param array $arrAttr, array with feature attributes
     */
    public function __construct($arrAttr) {
        // needed
        $this->name = $arrAttr['name'];
        // with default value
        $this->enabled = isset($arrAttr['enabled']) ? (boolean)$arrAttr['enabled'] : false;
        $this->type = isset($arrAttr['type']) ? $arrAttr['type'] : self::TYPE_RELEASE;
        $this->type = ($this->type === self::TYPE_RELEASE) ? self::TYPE_RELEASE : self::TYPE_BUSINESS;
        $this->params = isset($arrAttr['params']) ? $arrAttr['params'] : array();
        // init strategy instance
        $this->strategy = null;
        if (isset($arrAttr['strategy'])) {
            $strategyName = $arrAttr['strategy'];
            $strFile = dirname(__FILE__) . '/Strategy/' . $arrAttr['strategy'] . '.php';
            if (file_exists($strFile)){
                require_once $strFile;
                $strategyName = 'PFlag_Strategy_' . $arrAttr['strategy'];
            }
            
            if (class_exists($strategyName)) {
                $this->strategy = new $strategyName();
            } else {
                throw new Exception('No Such Strategy');
            }
        }

        $this->group = isset($arrAttr['group']) ? $arrAttr['group'] : self::GROUP_DEFAULT;
    }

    /**
     * true if feature is active
     */
    public function isActive() {
        if (!$this->strategy instanceof PFlag_Interface_Strategy) {
            return $this->enabled;
        }
        return $this->enabled && $this->strategy->isActive($this, PFlag_UserProvider::getCurrentUser());
    }

    public function getName() {
        return $this->name;
    }
    
    public function getParam($strArgName){
        if (isset($this->params[$strArgName])) {
            return $this->params[$strArgName];
        }
        return null;
    }
}