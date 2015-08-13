<?php
return [
    'code'        => require(__DIR__ . '/code.php'),
    'appId'       => 'I500_SOCIAL',
    'APP_CODE' => [
        'I500_SOCIAL' => 'DKJA@(SL)RssMAKDKas!L',
    ],
    'fast_dfs' =>[
        'ip_addr'=>"118.186.247.55",
        'port'=>'23000',
        'sock'=>-1,
        'store_path_index'=>0,
    ],
    'openLog'         => false, //true = 开始日志 false = 关闭日志
    'returnLogFile'   => '/tmp/return_log.txt', //返回值日志文件
    'paramsLogFile'   => '/tmp/params_log.txt', //客户端传递参数日志
    'imgHost'         => 'http://img.test.i500m.com/', //图片服务器
    'channelHost'     => 'http://channel.test.i500m.com/', //通道服务器
    'saveSms'         => true, //true = 保存短信内容入库 false = 不保存短信入库
    'openSmsChannel'  => false, //true = 开启短信通道 false = 关闭短信通道
    'mobilePreg'      => '/^1[34587][0-9]{9}$/',       //Mobile 验证规则
    'token_timeout'   => 7*24*60*60,                   //用户登陆token有效期
    'verify_code_timeout'   => 60*60,                  //用户短信验证码有效期
    'maxPageSize'     => 10,         //最大分页数
    /**环信相关配置**/
    'openHuanXin'     => true, //true = 开启环信 false = 关闭环信
    'passwordCode'    => '3e4r5t6y7f8d', //环信那边用户密码
    'hxClientID'      => 'YXA6C5R2MECSEeWmN6vKBaqwVQ',
    'hxClientSecret'  => 'YXA6vHECKE3XO06uVozdV9ceAjJBMhg',
    'hxTokenAPI'      => 'https://a1.easemob.com/iyangpin2015/i500social/token/', //环信登陆API
    'hxUsersAPI'      => 'https://a1.easemob.com/iyangpin2015/i500social/users/', //环信注册API

    'sign_debug'      => false,                        //false = 开启验证 true 关闭验证
];
