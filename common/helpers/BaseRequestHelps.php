<?php
/**
 * BaseRequest
 *
 * PHP Version 5
 *
 * @category  Social
 * @package   BaseRequest
 * @author    linxinliang <linxinliang@iyangpin.com>
 * @time      2015/8/12
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      linxinliang@iyangpin.com
 */

namespace common\helpers;

/**
 * BaseRequest
 *
 * @category Social
 * @package  BaseRequest
 * @author   linxinliang <linxinliang@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     linxinliang@iyangpin.com
 */
class BaseRequestHelps
{

    /**
     * Get
     * @param string $name    参数名
     * @param string $default 默认值
     * @param null   $filter  过滤方法
     * @return array|mixed|null|string
     */
    public static function get($name = '', $default = '', $filter = null)
    {
        return self::getParams($name, $default, $filter, $_GET);
    }

    /**
     * Post
     * @param string $name    参数名
     * @param string $default 默认值
     * @param null   $filter  过滤方法
     * @return array|mixed|null|string
     */
    public static function post($name = '', $default = '', $filter = null)
    {
        return self::getParams($name, $default, $filter, $_POST);
    }

    /**
     * Put
     * @param string $name    参数名
     * @param string $default 默认值
     * @param null   $filter  过滤方法
     * @return array|mixed|null|string
     */
    public static function put($name = '', $default = '', $filter = null)
    {
        static $_PUT = null;
        if (is_null($_PUT)) {
            parse_str(file_get_contents('php://input'), $_PUT);
        }
        return self::getParams($name, $default, $filter, $_PUT);
    }

    /**
     * 获取方法
     * @return string
     */
    public static function getMethod()
    {
        switch($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                $input = 'POST';
                break;
            case 'PUT':
                $input = 'PUT';
                break;
            default:
                $input = 'GET';
        }
        return $input;
    }

    /**
     * 获取参数
     * @param string $name    参数名称
     * @param string $default 默认值
     * @param null   $filter  过滤方法
     * @param null   $input   Input
     * @return array|mixed|null|string
     */
    public static function getParams($name='', $default = '', $filter = null, $input = null)
    {
        $filters    =   isset($filter) ? $filter : 'htmlspecialchars';
        $filters    =   !empty($filters) ? $filters : 'htmlspecialchars';
        if ('' == $name) {
            $data       =   $input;
            if ($filters) {
                if (is_string($filters)) {
                    $filters    =   explode(',', $filters);
                }
                foreach ($filters as $filter) {
                    $data   =   self::array_map_recursive($filter, $data); // 参数过滤
                }
            }
        } elseif (isset($input[$name])) { // 取值操作
            $data       =   $input[$name];
            if ($filters) {
                if (is_string($filters)) {
                    if (0 === strpos($filters, '/')) {
                        if (1 !== preg_match($filters, (string)$data)) {
                            // 支持正则验证
                            return   isset($default) ? $default : null;
                        }
                    } else {
                        $filters    =   explode(',', $filters);
                    }
                } elseif (is_int($filters)) {
                    $filters    =   array($filters);
                }

                if (is_array($filters)) {
                    foreach ($filters as $filter) {
                        if (function_exists($filter)) {
                            $data = is_array($data) ? self::array_map_recursive($filter, $data) : $filter($data); // 参数过滤
                        } else {
                            $data = filter_var($data, is_int($filter) ? $filter : filter_id($filter));
                            if (false === $data) {
                                return   isset($default) ? $default : null;
                            }
                        }
                    }
                }
            }

        } else { // 变量默认值
            $data       =    isset($default)?$default:null;
        }
        return $data;
    }

    /**
     * 递归
     * @param null $filter 过滤方法
     * @param null $data   数据
     * @return array
     */
    public static function array_map_recursive($filter, $data)
    {
        $result = array();
        foreach ($data as $key => $val) {
            $result[$key] = is_array($val)
                ? self::array_map_recursive($filter, $val)
                : call_user_func($filter, $val);
        }
        return $result;
    }
}