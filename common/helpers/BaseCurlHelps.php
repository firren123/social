<?php
/**
 * CURL 请求
 *
 * PHP Version 5
 *
 * @category  Social
 * @package   CURL
 * @author    linxinliang <linxinliang@iyangpin.com>
 * @time      2015/8/10
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      linxinliang@iyangpin.com
 */
namespace common\helpers;

use linslin\yii2\curl\Curl;
use yii\helpers\ArrayHelper;

/**
 * CURL 请求
 *
 * @category Social
 * @package  CURL
 * @author   linxinliang <linxinliang@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     linxinliang@iyangpin.com
 */
class BaseCurlHelps
{

    /**
     * Post 请求
     * @param string $url  URl
     * @param array  $post 传递的值
     * @return mixed
     */
    public static function post($url = '', $post = array())
    {
        $curl = new Curl();
        $response = $curl->reset()
            ->setOption(CURLOPT_POSTFIELDS, http_build_query($post))->post($url);
        $response = json_decode($response, true);
        return $response;
    }

    /**
     * Get 请求
     * @param string $url URL
     * @return array
     */
    public static function get($url = '')
    {
        $curl = new Curl();
        $response = $curl->get($url);
        $response = json_decode($response, true);
        return $response;
    }
}