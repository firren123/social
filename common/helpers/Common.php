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
     * 格式化数字
     * @param int $num 数字
     * @return string
     */
    public static function formatNumber($num = 0)
    {
        /**当数字超过万的时候进行格式化**/
        if ($num > 10000) {
            $num = round(($num/10000), 2).'w';
        }
        return $num;
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
                $temp = '校验码 '.$code.'，您正在登录爱500米，需要进行校验。如非本人操作请忽略本条信息';
                break;
            case 2 :
                /**第一次登陆发送密码**/
                $temp = '终于等到您，成为爱500米的一员。您的登录密码为 '.$code.'请及时登录修改。';
                break;
            case 3 :
                /**找回密码**/
                $temp = '校验码 '.$code.'，您正在找回爱500米密码，需要进行校验。如非本人操作请忽略本条信息';
                break;
            case 4 :
                /**绑定用户发送密码**/
                $temp = '终于等到您，成为爱500米的一员。您的登录密码为 '.$code.'请及时登录修改。';
                break;
            case 5 :
                /**注册发送验证码**/
                $temp = '校验码 '.$code.'，您正在注册爱500米账号，需要进行校验。如非本人操作请忽略本条信息';
                break;
            case 6 :
                /**绑定用户发送验证码**/
                $temp = '校验码 '.$code.'，您正在使用第三方登录绑定到您的爱500米账号。如非本人操作请忽略本条信息';
                break;
            default :
                $temp = '';
        }
        return $temp;
    }

    /**
     * 是否是身份证格式
     * @param string $number 身份证数字
     * @return bool
     */
    public static function isIdCard($number = '')
    {
        // 转化为大写，如出现x
        $number = strtoupper($number);
        //加权因子
        $wi = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
        //校验码串
        $ai = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
        //按顺序循环处理前17位
        $sigma = 0;
        for ($i = 0;$i < 17;$i++) {
            //提取前17位的其中一位，并将变量类型转为实数
            $b = (int) $number{$i};
            //提取相应的加权因子
            $w = $wi[$i];
            //把从身份证号码中提取的一位数字和加权因子相乘，并累加
            $sigma += $b * $w;
        }
        //计算序号
        $snumber = $sigma % 11;
        //按照序号从校验码串中提取相应的字符。
        $check_number = $ai[$snumber];
        if ($number{17} == $check_number) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 通过身份证获取生日
     * @param string $card 身份证
     * @return string
     */
    public static function getBirthdayByCard($card = '')
    {
        return strlen($card)==15 ? ('19' . substr($card, 6, 6)) : substr($card, 6, 8);
    }

    /**
     * 通过身份证获取性别
     * @param string $card 身份证
     * @return string
     */
    public static function getSexByCard($card = '')
    {
        //1=男 2=女
        return substr($card, (strlen($card)==15 ? -2 : -1), 1) % 2 ? '1' : '2';
    }

    /**
     * 通过身份证获取年龄
     * @param string $card 身份证
     * @return string
     */
    public static function getAgeByCard($card = '')
    {
        $date = strtotime(substr($card, 6, 8)); //获得出生年月日的时间戳
        $today = strtotime('today'); //获得今日的时间戳
        $diff = floor(($today-$date)/86400/365); //得到两个日期相差的大体年数
        //strtotime 加上这个年数后得到那日的时间戳后与今日的时间戳相比
        $age = strtotime(substr($card, 6, 8).' +'.$diff.'years') > $today ? ($diff+1) : $diff ;
        return $age;
    }

    /**
     * 通过日期获取星期
     * @param string $day 日期
     * @return string
     */
    public static function getWeek($day = '')
    {
        if (!empty($day)) {
            $week_array =array("日","一","二","三","四","五","六");
            return $week_array[@date("w", $day)];
        }
        return "";
    }

    /**
     * 距离当前时间展示方法
     * @param string $datetime 活跃时间
     * @param int    $nowtime  当前时间
     * @return bool|string
     */
    public static function timeAgo($datetime='', $nowtime = 0)
    {
        $datetime = strtotime($datetime);
        if (empty($nowtime)) {
            $nowtime = time();
        }
        $timediff = $nowtime - $datetime;
        $timediff = $timediff >= 0 ? $timediff : $datetime - $nowtime;
        // 秒
        if ($timediff < 60) {
            return $timediff . '秒前';
        }
        // 分
        if ($timediff < 3600 && $timediff >= 60) {
            return intval($timediff / 60) . '分钟前';
        }
        // 今天
        $today = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        if ($datetime >= $today) {
            return date('今天 H:i', $datetime);
        }
        // 昨天
        $yestoday = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
        if ($datetime >= $yestoday) {
            return date('昨天 H:i', $datetime);
        }
        // 今年月份
        $this_year = mktime(0, 0, 0, 1, 1, date('Y'));
        if ($datetime >= $this_year) {
            return date('m月d日 H:i', $datetime);
        }
        // 往年
        return date('Y年m月d日', $datetime);
    }
}
