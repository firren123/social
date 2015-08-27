<?php
/**
 * Common
 *
 * PHP Version 5
 *
 * @category  Social
 * @package   Common
 * @author    linxinliang <linxinliang@iyangpin.com>
 * @time      2015/8/12
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      linxinliang@iyangpin.com
 */

namespace common\helpers;

use Yii;

/**
 * Common
 *
 * @category Social
 * @package  Common
 * @author   linxinliang <linxinliang@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     linxinliang@iyangpin.com
 */
class Common
{
    /**
     * 截取字符串
     * @param string $string 字符串
     * @param string $length 限制长度
     * @param string $etc    后缀
     * @return string
     */
    public static function truncate_utf8_string($string, $length, $etc = '...')
    {
        $result = '';
        $string = html_entity_decode(trim(strip_tags($string)), ENT_QUOTES, 'UTF-8');
        $strlen = strlen($string);
        for ($i = 0; (($i < $strlen) && ($length > 0)); $i++) {
            if ($number = strpos(str_pad(decbin(ord(substr($string, $i, 1))), 8, '0', STR_PAD_LEFT), '0')) {
                if ($length < 1.0) {
                    break;
                }
                $result .= substr($string, $i, $number);
                $length -= 1.0;
                $i += $number - 1;
            } else {
                $result .= substr($string, $i, 1);
                $length -= 0.5;
            }
        }
        $result = htmlspecialchars($result, ENT_QUOTES, 'UTF-8');
        if ($i < $strlen) {
            $result .= $etc;
        }
        return $result;
    }

    /**
     * 二维数组去重
     * @param array  $arr 数组
     * @param string $key 键
     * @return mixed
     */
    public static function arrUnique($arr = [], $key = '')
    {
        $tmp_arr = array();
        foreach ($arr as $k => $v) {
            if (in_array($v[$key], $tmp_arr)) {
                //搜索$v[$key]是否在$tmp_arr数组中存在，若存在返回true
                unset($arr[$k]);
            } else {
                $tmp_arr[] = $v[$key];
            }
        }
        //sort($arr); //sort函数对数组进行排序
        return $arr;
    }

    /**
     * 验证手机号
     * @param string $mobile 手机号
     * @return bool
     */
    public static function validateMobile($mobile)
    {
        $preg = Yii::$app->params['mobilePreg'];
        if (preg_match($preg, $mobile)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取客户端IP
     * @return string
     */
    public static function getIp()
    {
        $ip = '';
        if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
            $ip = getenv('REMOTE_ADDR');
        } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    /**
     * 获取6位随机数
     * @return int
     */
    public static function getRandomNumber()
    {
        return mt_rand(100000, 999999);
    }

    /**
     * 获取参数
     * @param string $param1 第一个参数
     * @param string $param2 第二个参数
     * @return int
     */
    public static function C($param1 = '', $param2 = '')
    {
        if ($param1 && $param2) {
            return \Yii::$app->params[$param1][$param2];
        } else {
            return \Yii::$app->params[$param1];
        }
    }

    /**
     * 获取短信模板
     * @param int $type 类型
     * @param int $code 验证码
     * @return string
     */
    public static function getSmsTemplate($type = 1, $code = 0)
    {
        switch ($type) {
            case 1 :
                /**登陆发送验证码**/
                $temp = '校验码 '.$code.'，您正在登录爱500米，需要进行校验。如非本人操作请忽略本条信息［请勿向任何人提供您收到的短信校验码］';
                break;
            case 2 :
                /**第一次登陆发送密码**/
                $temp = '终于等到您，成为爱500米的一员。您的登录密码为 '.$code.'请及时登录修改。';
                break;
            case 3 :
                /**找回密码**/
                $temp = '校验码 '.$code.'，您正在找回爱500米密码，需要进行校验。如非本人操作请忽略本条信息［请勿向任何人提供您收到的短信校验码］';
                break;
            case 4 :
                /**绑定用户发送密码**/
                $temp = '终于等到您，成为爱500米的一员。您的登录密码为 '.$code.'请及时登录修改。';
                break;
            case 5 :
                /**注册发送验证码**/
                $temp = '校验码 '.$code.'，您正在注册爱500米账号，需要进行校验。如非本人操作请忽略本条信息［请勿向任何人提供您收到的短信校验码］';
                break;
            case 6 :
                /**绑定用户发送验证码**/
                $temp = '校验码 '.$code.'，您正在使用第三方登录绑定到您的爱500米账号。如非本人操作请忽略本条信息［请勿向任何人提供您收到的短信校验码］';
                break;
            default :
                $temp = '';
        }
        return $temp;
    }
}
