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
            case 7 :
                /**绑定用户银行卡发送验证码短信**/
                $temp = '校验码 '.$code.'，您正在绑定银行卡账号。如非本人操作请忽略本条信息';
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
        for ($i = 0; $i < 17; $i++) {
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
        $birthday = strlen($card)==15 ? ('19' . substr($card, 6, 6)) : substr($card, 6, 8);
        if (strlen($card) != 15) {
            $year  = substr($birthday, 0, 4);
            $month = substr($birthday, 4, 2);
            $day   = substr($birthday, -2);
            return $year.'-'.$month.'-'.$day;
        }
        return $birthday;
    }

    /**
     * 通过身份证获取性别
     * @param string $card 身份证
     * @return string
     */
    public static function getSexByCard($card = '')
    {
        //1=男 2=女
        $sex = (int)substr($card, 16, 1);
        return $sex % 2 === 0 ? '2' : '1';
        //return substr($card, (strlen($card)==15 ? -2 : -1), 1) % 2 ? '1' : '2';
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
     * 通过身份证获取星座
     * @param string $card 身份证
     * @return string
     */
    public static function getConstellationByCard($card = '')
    {
        $bir   = substr($card, 10, 4);
        $month = (int)substr($bir, 0, 2);
        $day   = (int)substr($bir, 2);
        $strValue = '0';
        if (($month == 1 && $day >= 20) || ($month == 2 && $day <= 18)) {
            $strValue = "1"; //水瓶座
        } elseif (($month == 2 && $day >= 19) || ($month == 3 && $day <= 20)) {
            $strValue = "2"; //双鱼座
        } elseif (($month == 3 && $day > 20) || ($month == 4 && $day <= 19)) {
            $strValue = "3"; //白羊座
        } elseif (($month == 4 && $day >= 20) || ($month == 5 && $day <= 20)) {
            $strValue = "4"; //金牛座
        } elseif (($month == 5 && $day >= 21) || ($month == 6 && $day <= 21)) {
            $strValue = "5"; //双子座
        } elseif (($month == 6 && $day > 21) || ($month == 7 && $day <= 22)) {
            $strValue = "6"; //巨蟹座
        } elseif (($month == 7 && $day > 22) || ($month == 8 && $day <= 22)) {
            $strValue = "7"; //狮子座
        } elseif (($month == 8 && $day >= 23) || ($month == 9 && $day <= 22)) {
            $strValue = "8"; //处女座
        } elseif (($month == 9 && $day >= 23) || ($month == 10 && $day <= 23)) {
            $strValue = "9"; //天秤座
        } elseif (($month == 10 && $day > 23) || ($month == 11 && $day <= 22)) {
            $strValue = "10"; //天蝎座
        } elseif (($month == 11 && $day > 22) || ($month == 12 && $day <= 21)) {
            $strValue = "11"; //射手座
        } elseif (($month == 12 && $day > 21) || ($month == 1 && $day <= 19)) {
            $strValue = "12"; //魔羯座
        }
        return $strValue;
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
            return $week_array[@date("w", strtotime($day))];
        }
        return "";
    }

    /**
     * 隐藏身份证信息
     * @param string $card 身份证
     * @return string
     */
    public static function hiddenUserCard($card = '')
    {
        return substr_replace($card, '****', 10, 4);
    }

    /**
     * 距离当前时间展示方法 - 【权威的】
     * @param string $datetime 活跃时间
     * @param int    $nowtime  当前时间
     * @return bool|string
     */
    public static function timeAgoBAK($datetime='', $nowtime = 0)
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

    /**
     * 距离当前时间展示方法 - 【产品非要这样的，沟通无果,所以...】
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
            return '今天';
        }
        // 分
        if ($timediff < 3600 && $timediff >= 60) {
            return '今天';
        }
        // 今天
        $today = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        if ($datetime >= $today) {
            return '今天';
        }
        // 昨天
        $yestoday = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
        if ($datetime >= $yestoday) {
            return '昨天';
        }
        // 今年月份
        $this_year = mktime(0, 0, 0, 1, 1, date('Y'));
        if ($datetime >= $this_year) {
            return date('m月d日', $datetime);
        }
        // 往年
        return date('m月d日Y年', $datetime);
    }

    /**
     * 根据经纬度获取两个两点之间的距离
     * @param int $lat1 纬度
     * @param int $lng1 经度
     * @param int $lat2 纬度
     * @param int $lng2 经度
     * @return float
     */
    public static function getDistance($lat1 = 0, $lng1 = 0, $lat2 = 0, $lng2 = 0)
    {
        //地球半径
        $R = 6378137;
        //将角度转为弧度
        $radLat1 = deg2rad($lat1);
        $radLat2 = deg2rad($lat2);
        $radLng1 = deg2rad($lng1);
        $radLng2 = deg2rad($lng2);
        //结果
        $s = acos(cos($radLat1)*cos($radLat2)*cos($radLng1-$radLng2)+sin($radLat1)*sin($radLat2))*$R;
        //精度
        $s = round($s* 10000)/10000;
        if ($s > 1000) {
            return round($s/1000, 2).'km';
        } else {
            return round($s).'m';
        }
    }
}
