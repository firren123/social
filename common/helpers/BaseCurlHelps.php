<?php
/**
 * 一行的文件介绍
 *
 * PHP Version 5
 * 可写多行的文件相关说明
 *
 * @category  Wap
 * @package   Member     (这里写模块名)
 * @author    liubaocheng <liubaocheng@iyangpin.com>
 * @time      15/3/20 上午11:14
 * @copyright 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com
 * @link      liubaocheng@iyangpin.com
 */

namespace common\helpers;

use linslin\yii2\curl\Curl;
use yii\helpers\ArrayHelper;

class BaseCurlHelps
{

    public static function get($url = '', $type = 'server')
    {
        $host = '';
        $app_id = \Yii::$app->params['appId'];
        $app_key = \Yii::$app->params['appKey'];

        $timestamp = time();
        $data = array();
        if('server' == $type){
            $host = \Yii::$app->params['serverUrl'];
            $ex = explode('?', $url);
            if(isset($ex[1])){
                $param = explode('&', $ex[1]);
                $data = array();
                foreach($param as $k=>$v){
                    $ex_v = explode('=', $v);
                    $data[$ex_v[0]] = $ex_v[1];
                }
            }
            $sign = self::_createSign($app_key, $timestamp, $data);
            if (stripos($url, '?') === false) {
                $url .= '?appId=' . $app_id . '&timestamp=' . $timestamp . '&sign=' . $sign;
            } else {
                $url .= '&appId=' . $app_id . '&timestamp=' . $timestamp . '&sign=' . $sign;
            }
        }elseif('api' == $type){
            $access_token = \Yii::$app->params['access_token'];
            $host = \Yii::$app->params['apiUrl'];
            if (stripos($url, '?') === false) {
                $url .= '?access-token=' . $access_token;
            } else {
                $url .= '&access-token=' . $access_token;
            }
        }else{

        }
        $url = strstr($url, 'http://') ? $url : $host.$url;

        $curl = new Curl();
        $response = $curl->get($url);
        $response = json_decode($response,true);
        return $response;
    }

    public static function post($url = '', $post = array(), $type = 'server'){
        $host = '';
        $app_id = \Yii::$app->params['appId'];
        $app_key = \Yii::$app->params['appKey'];
        $timestamp = time();
        if('server' == $type){
            $host = \Yii::$app->params['serverUrl'];
            $sign = self::_createSign($app_key, $timestamp, $post);
            $post['appId']      = $app_id;
            $post['timestamp']  = $timestamp;
            $post['sign']       = $sign;
        }elseif('api' == $type){
            $host = \Yii::$app->params['apiUrl'];
            $access_token = \Yii::$app->params['access_token'];
            if (stripos($url, '?') === false) {
                $url .= '?access-token=' . $access_token;
            } else {
                $url .= '&access-token=' . $access_token;
            }
        }else{

        }
        $url = strstr($url, 'http://') ? $url : $host.$url;
        $curl = new Curl();
        $response = $curl->reset()
            ->setOption(
                CURLOPT_POSTFIELDS,
                http_build_query($post
                ))
            ->post($url);
        $response = json_decode($response,true);
        return $response;
    }

    /**
     * 强制restful put 修改数据
     * @param string $url url中需带要修改的id
     * @param array $post
     * @return mixed
     */
    public static function put($url = '', $post = array()){
        $host = \Yii::$app->params['apiUrl'];
        $access_token = \Yii::$app->params['access_token'];
        if (stripos($url, '?') === false) {
            $url .= '?access-token=' . $access_token;
        } else {
            $url .= '&access-token=' . $access_token;
        }
        $url = strstr($url, 'http://') ? $url : $host.$url;
        $curl = new Curl();
        $response = $curl->reset()
            ->setOption(
                CURLOPT_POSTFIELDS,
                http_build_query($post)
            )
            ->put($url);
        $response = json_decode($response,true);
        return $response;
    }

    /**
     * 删除 restful api
     * @param string $url
     * @return mixed
     */
    public static function delete($url = ''){


        $host = \Yii::$app->params['apiUrl'];
        $access_token = \Yii::$app->params['access_token'];
        if (stripos($url, '?') === false) {
            $url .= '?access-token=' . $access_token;
        } else {
            $url .= '&access-token=' . $access_token;
        }
        $url = strstr($url, 'http://') ? $url : $host.$url;
        $curl = new Curl();
        $response = $curl->delete($url);
        $response = json_decode($response,true);
        return $response;
    }


    private static function _createSign($app_key = '', $timestamp = '', $data = array()){

        $val = '';
        if($data){
            krsort($data);
            foreach($data as $k=>$v){
                $val .= $v;
            }
        }
        file_put_contents('/tmp/wap_data.log', var_export($data, true), FILE_APPEND);
        return md5(md5(md5($app_key.$timestamp).md5($timestamp)).md5($val));
    }
}