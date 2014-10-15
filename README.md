##pflag

pflag是一个管理php代码特性开关的lib库。

在我们的项目持续集成实践中，多人主干开发定期发布往往是一个常态，例如对于一个两周一次发布的项目，若开发一个超过两周或发布时间不匹配的特性，如何让未完成的特性在线上隐藏？

pflag正是设计来解决该类问题的工具，当然pflag使用场景可能不局限于此，如下列出的pflag使用的主要场景：

   * 特性管理
   
     代码持续发布时，不同Feature开发进度不一，若某些Feature未完成时需要发布，可以通过控制Feature开关来隐藏Feature功能。
   * 流量控制
   
     轻松控制让哪些用户要进入哪个版本，如某个新加入的特性可以仅开放给部分用户，以便进行AB-test实验。还可以指定特定策略来进行流量切换。

###基本概念
   * release toggles: 为版本控制新建的特性开关
   * business toggles: 为流量控制新建的特性开关
   * feature开关状态: 当前feature是否开启标识，取值：enabled或disabled
   * feature发布状态: 当前feature是否激活， 取值：active或inactive<br>
     注：是否激活即，在执行过程中该feature是否对用户可见
   * activation strategy：激活策略，用于判断feature是否对用户可见的
     注：所以，一个feature是active状态 = 开关状态enabled + 发布状态active(没有激活策略或激活策略计算为true)
   * feature group: 表示一组特性集合。在特性很多后方便分组显示。group不含其它属性。
   * feature user: 运行时表征一个使用flag的用户，可包含一些用户属性。

###使用示例
step1: 增加ini配置
```
[product]
;;新特性开关
features.MYFEATURE1.enabled = false
features.MYFEATURE1.type = release

;;灰度发布小流量
features.MYFEATURE2.enabled = true
features.MYFEATURE2.type = business
features.MYFEATURE2.strategy = Gradual
features.MYFEATURE2.params.percent = 0.10

[dev:product]
features.MYFEATURE1.enabled = true
```
step2: 增加代码开关
```
// 载入配置
PFlag_Features::loadIniConf(CONF_PATH . 'feature.ini');
// 获取特性
$feature = PFlag_Features::get('MYFEATURE2');
// check是否激活
if ($feature->isActive()) {
    // do something magic
}
```

pflag默认提供两种配置方式: iniconfig和phpconfig。载入方式分别如下：
```
PFlag_Features::loadIniConf(CONF_PATH . '/features.ini');
PFlag_Features::loadArrConf(CONF_PATH . '/features.php'); 
```
CONF_PATH为配置文件所在路径。

如上，两步即可使用。

###配置字段

每个feature可配置的属性如下：

| *字段* | *默认值* | *备注* |
| ------------- | ------------- | ------------- |
| enabled | false | feature总开关 |
| type | 'release' | 取值release或business |
| strategy | null | 填写激活策略类名 |
| params | null | 参数数组 |
| group | 'default' | 标识feature所属的组, 暂未启用|

注：
>1. 约定，使用仅特征开关功能时将type属性值设为release。e.g 示例的MYFEATURE1
>2. 在使用流量控制时，设置feature的type属性值为business，这时需要配置激活策略strategy。e.g 示例的MYFEATURE2

###激活策略Strategy

只要为feature配置strategy属性，则会启动对激活策略的验证。

所有激活策略类都继承自PFlag_Interface_Strategy接口。若不为feature设置strategy，表示无激活策略，只需要enabled=true即可激活。

PFlag自带几种激活策略：ClientIp、Gradual、ReleaseDate

#####ClientIp
按客户端Ip发布策略。需要的参数：

| *参数* | *默认值* | *备注* |
| ------------- | ------------- | ------------- |
| white_list | 无，array | 白名单ip,支持简单正则  |

根据客户端ip来判断是否激活特性，使用配置(以phpconfig为例）
```
'FEATUREX' => array(
    'enabled' => true,
    'type' => 'business',
    'strategy' => 'ClientIp',
    'params' => array(
        'white_list' => array('172.22.163.158',
        '172.23.*.*')
    ),
),
```

#####Gradual
灰度发布策略。需要的参数：

| *参数* | *默认值* | *备注* |
| ------------- | ------------- | ------------- |
| percent | 无，0~1 | 发布百分比  |

该策略可以设置flag用户实例，设置方式：
```
$fuser = new PFlag_User_Default();
$fuser->setUserid($this->_user->getPassid());
PFlag_UserProvider::bind($fuser);
```
若无设置则采用随机策略。增加配置如下（e.g 10%小流量）：
```
feature.FEATUREX.enable = true
feature.FEATUREX.type = business
feature.FEATUREX.strategy = Gradual
feature.FEATUREX.params.percent = 0.10
```
注: 用户灰度发布是按用户发布的，同一用户看到特性始终是一致的

#####ReleaseDate
按发布时间发布策略。支持参数：

| *参数* | *默认值* | *备注* |
| ------------- | ------------- | ------------- |
| start_date | 无，必选 | 发布开始时间  |
| end_date |  可选，默认不结束 | 发布结束时间 |
 
e.g 在做类似双11活动的代码可采用此发布策略

#####自定义策略
通过实现接口PFlag_Interface_Strategy，你可以自定义策略类；
如：新建的策略类PFlag_Strategy_YourStrategy，主要需要实现的方法
```
/**
 * check if strategy is active 
 * @param PFlag_Interface_Feature $feature
 * @param PFlag_Interface_User $user
 * @return true | false
 */
public function isActive(PFlag_Interface_Feature $feature, PFlag_Interface_User $user=null);
```

配置文件：
```
feature.FEATUREX.enable = true
feature.FEATUREX.type = business
feature.FEATURE.strategy = YourStrategy
```

###使用流程和规范

pflag最终目的是能为团队开发服务，提高敏捷效率。为自己的团队制定合适的流程和规范极为重要。以下为在实践中的一些建议。

1. flag命名规范
   * 可使用 *团队名_项目名_功能名(_子功能名)_* 对开关进行命名，全部使用大写字母，例：UC_VS_HOMEPAGE_V3；
   * 为每个开关配置增加必要注释，指明开关的作用、迭代版本或上线日期、预期清理日期等
2. flag数量限制
   为减少开关滥用造成后期维护成本的上升，需控制开关在代码中的使用次数。建议开关的数量限制为：
   * 代码中所有release类型开关不超过10个
   * 代码中所有business类型开关不超过5个
   * 代码中开关出现的次数不超过100次
   * 每轮迭代内确保上线的功能可不增加开关
3. 代码中添加flag的方式
   * 新增的代码建议写在新的类中，只在新功能的入口处添加开关
   * 修改方法内代码可在方法调用前添加开关，保留原方法，添加新方法
   * 为避免开关间的依赖耦合，开关不能嵌套使用
4. flag及时清理
   * release类型开关，上线功能稳定后的下个迭代删除
   * business类型开关，小流量转全流量后的下个迭代删除

###相关资料
   * 敏捷大师Martin Fowler的特性开关简介 http://martinfowler.com/bliki/FeatureToggle.html
   * c标志位管理 http://code.google.com/p/gflags/
   * java标准位管理 http://www.togglz.org/
