<?php
/**
 * 标识码
 *
 * PHP Version 5
 *
 * @category  Social
 * @package   BASE
 * @author    linxinliang <linxinliang@iyangpin.com>
 * @time      2015/8/05
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      linxinliang@iyangpin.com
 */
return [
    '200'  => 'SUCCESS',
    '400'  => '服务器繁忙',
    /**500 - 600 段 表示验签相关错误**/
    /**600 - 700 段 表示用户级别错误**/

    '501' => '缺少参数 appId',
    '502' => '缺少参数 timestamp',
    '503' => '缺少参数 sign',
    '504' => '缺少参数 mobile',
    '505' => '签名验证失败',
    '506' => 'appID 错误',
    '507' => 'Token 不能为空',
    '508' => 'Token 过期',

    '601' => '用户禁用，请联系管理员',
    '602' => '账号不存在',
    '603' => '账户异常请重新登陆',
    '604' => '用户名不能为空',
    '605' => '手机号格式不正确',
    '606' => '密码不能为空',
    '607' => '密码错误',
    '608' => '验证码不能为空',
    '609' => '验证码失效',
    '610' => '验证码错误',
    '611' => '发送验证码失败',

];