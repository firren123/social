<?php
return [
    'code'        => require(__DIR__ . '/code.php'),
    'appId'       => 'I500_SOCIAL',
    'APP_CODE' => [
        'I500_SOCIAL' => 'DKJA@(SL)RssMAKDKas!L',
    ],
    'openSSDB'        => false, //true = 开启 false = 关闭
    'SSDBCacheTime'   => 60, //SSDB 缓存时间 60秒
    'openLog'         => true, //true = 开始日志 false = 关闭日志
    'returnLogFile'   => '/tmp/return_log.txt', //返回值日志文件
    'paramsLogFile'   => '/tmp/params_log.txt', //客户端传递参数日志
    'hongBaoShareImg'     => 'http://img.i500m.com/uploads/custom/20150909/hongbao.jpg',  //红包分享图片
    'hongBaoShareTitle'   => 'i500m领券啦——积少成多，不做败家婆！',  //红包分享文字标题
    'hongBaoShareText'    => '我在这里派发i500m抵用券啦。心若在钱就在，能省一块是一块！',  //红包分享文字内容
    'hongBaoHost'     => 'http://social.test.i500m.com/hongbao?sign=',  //红包路径
    'imgHost'         => 'http://img.test.i500m.com/', //图片服务器
    'channelHost'     => 'http://channel.test.i500m.com/', //通道服务器
    'saveSms'         => true, //true = 保存短信内容入库 false = 不保存短信入库
    'openSmsChannel'  => true, //true = 开启短信通道 false = 关闭短信通道
    'openUserActiveTime' => true, //true = 开启记录用户活跃时间 false = 关闭
    'mobilePreg'      => '/^1[34587][0-9]{9}$/',       //Mobile 验证规则
    'token_timeout'   => 7*24*60*60,                   //用户登陆token有效期
    'verify_code_timeout'   => 60*60,                  //用户短信验证码有效期
    'maxPageSize'     => 10,         //最大分页数
    'shopScope'       => 2,         //附近商家范围 单位(公里)
    /**环信相关配置**/
    'openHuanXin'     => true, //true = 开启环信 false = 关闭环信
    'passwordCode'    => '3e4r5t6y7f8d', //环信那边用户密码
    'hxClientID'      => 'YXA6C5R2MECSEeWmN6vKBaqwVQ',
    'hxClientSecret'  => 'YXA6vHECKE3XO06uVozdV9ceAjJBMhg',
    'hxTokenAPI'      => 'https://a1.easemob.com/iyangpin2015/i500social/token/', //环信登陆API
    'hxUsersAPI'      => 'https://a1.easemob.com/iyangpin2015/i500social/users/', //环信注册API
    'regAddFriendCustomerService' => true,  //true = 注册添加客服为好友 false = 不添加
    'customerServiceUserName'  => 'customservice',   //客服账号

    'sign_debug'      => false,                        //false = 开启验证 true 关闭验证

    //恒信通相关
    'hxt_soap_url' => 'http://114.113.238.50:8765/Service.asmx?WSDL',//恒信通会员缴费业务接口
    'hxt_TerminalID' => '81030003',
    'hxt_KeyID' => '25176948',
    'hxt_MacKey' => '1101010101111001000101010110100101110111010110111100100011110111001001001001011001101010100110101000101010101010100101010100101110101001010010101110110010011001011101001100001010010001101010100100010110011101111011000101001010100010110011101001011010110011',
];
