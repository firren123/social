<?php
return [
    'code'        => require(__DIR__ . '/code.php'),
    'appId'       => 'I500_SOCIAL',
    'APP_CODE' => [
        'I500_SOCIAL' => 'DKJA@(SL)RssMAKDKas!L',
    ],
    'mobilePreg'      => '/^1[34587][0-9]{9}$/',      //Mobile 验证规则
    'token_timeout'   => 7*24*60*60,                  //用户登陆token有效期
    'verify_code_timeout'   => 60*60,            //用户短信验证码有效期
    'sign_debug'      => true,                       //false = 开启验证 true 关闭验证
];
