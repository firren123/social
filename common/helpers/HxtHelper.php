<?php
/**
 * 恒信通相关
 *
 * PHP Version 5
 *
 * @category  SOCIAL
 * @package   HELPER
 * @author    zhengyu <zhengyu@iyangpin.com>
 * @time      15/8/25 10:19
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      zhengyu@iyangpin.com
 */


namespace common\helpers;

use Yii;


/**
 * 恒信通相关
 *
 * @category SOCIAL
 * @package  HELPER
 * @author   zhengyu <zhengyu@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     zhengyu@iyangpin.com
 */
class HxtHelper
{
    //private $_default_arr_where = array();
    //private $_default_str_andwhere = '';
    //private $_default_arr_order = array();
    //private $_default_str_field = '*';
    //private $_default_int_offset = -1;
    //private $_default_int_limit = -1;

    private $_switch_log = 1;//1=开启 0=关闭
    private $_service = '';

    /**
     * 构造函数
     *
     * Author zhengyu@iyangpin.com
     */
    public function __construct()
    {
        //$this->_sep1 = '----------';
        //$this->_sep2 = $this->_sep1 . $this->_sep1;
        //$this->_sep4 = $this->_sep2 . $this->_sep2;

        $this->service = Yii::$app->params['hxt_soap_url'];
    }

    /**
     * 查询接口
     *
     * Author zhengyu@iyangpin.com
     *
     * @param array $arr 接口所需参数
     *
     * @return void
     */
    public function query($arr)
    {
        $client = new \SoapClient($this->service);

        $client->soap_defencoding = 'utf-8';
        $client->decode_utf8 = false;
        $client->xml_encoding = 'utf-8';

        //$param = array('param1'=>'1', 'param2'=>'2');
        $param = $arr;

        $result = $client->__soapCall("HXTServiceQuery", array($param));

        if (is_soap_fault($result)) {
            $str = "[SOAP Fault],faultcode=" . $result->faultcode . ",faultstring=" . $result->faultstring;
            $this->_zlog($str, 'soap_exception');
        } else {
            var_dump($result);exit;
            var_dump($result->HXTServiceQueryResult);exit;
            var_dump($result->HXTServiceQueryResult->any);exit;
        }
    }



    /**
     * 记录日志
     *
     * Author zhengyu@iyangpin.com
     *
     * @param string $str  内容
     * @param string $type 类型
     *
     * @return void
     */
    private function _zlog($str = '', $type = '')
    {
        if ($this->_switch_log !== 1) {
            return;
        }

        $str_today = date("Ymd", time());

        $content = date("H:i:s ") . $str;

        if ($type == '') {
            $log_dir = "/tmp/soaplog";
            if (!is_dir($log_dir)) {
                @mkdir($log_dir, 0700);
            }
            file_put_contents($log_dir . '/tmp_soap_' . $str_today, $content, FILE_APPEND);
        } elseif ($type == 'soap_exception') {
            $log_dir = "/tmp/zlog";
            if (!is_dir($log_dir)) {
                @mkdir($log_dir, 0700);
            }
            $content = date("Y-m-d_H:i:s ") . $str;
            file_put_contents($log_dir . '/tmp_ssdb_exception', $content, FILE_APPEND);
        } else {
        }

        return;
    }








}
