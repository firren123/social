<?php
/**
 * APP基类
 *
 * PHP Version 5
 *
 * @category  Social
 * @package   BASE
 * @author    linxinliang <linxinliang@iyangpin.com>
 * @time      2015/8/05 09:21
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      linxinliang@iyangpin.com
 */

namespace frontend\modules\v1\controllers;

use common\helpers\Common;
use common\helpers\RequestHelper;
use common\helpers\CurlHelper;
use frontend\models\i500_social\User;
use frontend\models\i500_social\UserActiveTime;
use frontend\models\i500_social\UserToken;
use frontend\models\i500_social\UserSms;
use yii\web\Controller;

/**
 * BASE
 *
 * @category Social
 * @package  BASE
 * @author   linxinliang <linxinliang@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     linxinliang@iyangpin.com
 */
class BaseController extends Controller
{
    protected $params = null;
    public $shop_id;

    /**
     * 初始化
     * @return array
     */
    public function init()
    {
        parent::init();
        //获取请求类型
        $method = RequestHelper::getMethod();
        switch ($method) {
            case 'POST':
                $this->params = RequestHelper::post();
                break;
            case 'PUT' :
                $this->params = RequestHelper::put();
                break;
            default :
                $this->params = RequestHelper::get();
                break;
        }
        $this->saveLog(Common::C('returnLogFile'), var_export($this->params, true));
        if (!\Yii::$app->params['sign_debug']) {
            if (!isset($this->params['appId'])) {
                $this->returnJsonMsg('501', [], Common::C('code', '501'));
            }
            $app_id = $this->params['appId'];
            unset($this->params['appId']);

            if (!isset($this->params['timestamp'])) {
                $this->returnJsonMsg('502', [], Common::C('code', '502'));
            }
            $timestamp = $this->params['timestamp'];
            unset($this->params['timestamp']);

            if (!isset($this->params['sign'])) {
                $this->returnJsonMsg('503', [], Common::C('code', '503'));
            }
            $sign = $this->params['sign'];
            unset($this->params['sign']);

            if (!isset($this->params['dev'])) {
                $this->returnJsonMsg('509', [], Common::C('code', '509'));
            }
            if (!in_array($this->params['dev'], array('1', '2', '3', '4'))) {
                $this->returnJsonMsg('510', [], Common::C('code', '510'));
            }
            $dev = $this->params['dev'];
            unset($this->params['dev']);

            if (isset($this->params['uid']) && $this->params['uid']) {
                /**验证签名**/
                $this->_checkToken($this->params['token'], $this->params['mobile']);
            }
            if (isset($this->params['token'])) {
                unset($this->params['token']);
            }
            $this->_verifySign($sign, $app_id, $timestamp);       //验证签名
        }
    }

    /**
     * 签名验证
     * @param string $sign      签名
     * @param string $app_id    AppID
     * @param string $timestamp 时间戳
     * @return array
     */
    private function _verifySign($sign = '', $app_id = '', $timestamp = '')
    {
        if ($this->_createSign($app_id, $timestamp) != $sign) {
            $this->returnJsonMsg('505', [], Common::C('code', '505'));
        }
    }

    /**
     * 构造签名
     * @param string $app_id    AppID
     * @param string $timestamp 时间戳
     * @return string
     */
    private function _createSign($app_id = '', $timestamp = '')
    {
        $app_code = \Yii::$app->params['APP_CODE'];
        if (!isset($app_code[$app_id])) {
            $this->returnJsonMsg('506', [], Common::C('code', '506'));
        }
        $val  = '';
        if ($this->params) {
            $params = $this->params;
            //ksort($params);
            if (isset($params['json_str'])) {
                $params['json_str'] = $_POST['json_str'];
            }
            foreach ($params as $k=>$v) {
                $v = strtolower($v);
                $val .= $v;
            }
        }
        $sign = md5(md5(md5($app_code[$app_id].$timestamp).md5($timestamp)).md5($val));
        return $sign;
    }

    /**
     * 检查Token
     * @param string $token  Token
     * @param string $mobile 手机号
     * @return array
     */
    private function _checkToken($token = '', $mobile = '')
    {
        if (!$token) {
            $this->returnJsonMsg('507', [], Common::C('code', '507'));
        }
        $user_model = new User();
        $user_where['mobile']     = $mobile;
        $user_where['is_deleted'] = '2';
        $user_fields = 'id,status';
        $user_info = $user_model->getInfo($user_where, true, $user_fields);
        if (!empty($user_info)) {
            if ($user_info['status'] == '1') {
                $this->returnJsonMsg('601', [], Common::C('code', '601'));
            }
        } else {
            $this->returnJsonMsg('602', [], Common::C('code', '602'));
        }

        $user_token_model = new UserToken();
        $user_token_where['mobile'] = $mobile;
        $user_token_fields = 'id,token,create_time';
        $user_token_info = $user_token_model->getInfo($user_token_where, true, $user_token_fields);
        if (!empty($user_token_info)) {
            $token_timeout = strtotime($user_token_info['create_time']) + Common::C('token_timeout');
            if ($token != $user_token_info['token'] || time() > $token_timeout) {
                $this->returnJsonMsg('508', [], Common::C('code', '508'));
            }
        } else {
            $this->returnJsonMsg('603', [], Common::C('code', '603'));
        }
    }

    /**
     * 返回JSON格式的数据
     * @param string $code    错误代码
     * @param array  $data    数据
     * @param string $message 错误说明
     * @return array
     */
    public function returnJsonMsg($code='',$data=array(),$message='')
    {
        $arr = array(
            'code' => $code,
            'data' => $data,
            'message' => $message,
        );
        $this->saveLog(Common::C('paramsLogFile'), var_export($arr, true));
        $ret = json_encode($arr);
        $ret_str = str_replace('null', '""', $ret);
        die($ret_str);
    }

    /**
     * 发送短信通道
     * @param string $mobile  手机号
     * @param string $content 短信内容
     * @return array
     */
    public function sendSmsChannel($mobile = '', $content = '')
    {
        if (Common::C('openSmsChannel')) {
            $url = Common::C('channelHost').'sms/get-add';
            $arr['mobile']  = $mobile;
            $arr['content'] = $content;
            $rs = CurlHelper::post($url, $arr, true);
            if ($rs['code']=='200') {
                return true;
            }
            return false;
        } else {
            return true;
        }
    }

    /**
     * 保存用户发送短信信息
     * @param array $data 数据
     * @return array
     */
    public function saveUserSms($data = array())
    {
        if (Common::C('saveSms')) {
            $user_sms_model = new UserSms();
            return $user_sms_model->insertInfo($data);
        } else {
            return true;
        }
    }

    /**
     * 记录用户活跃信息
     * @param array $data 数据
     * @return bool
     */
    public function saveUserActiveTime($data = array())
    {
        if (Common::C('openUserActiveTime')) {
            if (!empty($data['mobile'])) {
                $user_user_active_time_model = new UserActiveTime();
                $user_user_active_time_where['mobile'] = $data['mobile'];
                $info = $user_user_active_time_model->getInfo($user_user_active_time_where, true, 'id');
                if (empty($info)) {
                    //新增
                    return $user_user_active_time_model->insertInfo($data);
                } else {
                    //编辑
                    $user_user_active_time_data['create_time'] = date('Y-m-d H:i:s', time());
                    return $user_user_active_time_model->updateInfo($user_user_active_time_data, $user_user_active_time_where);
                }
            } else {
                return true;
            }
        } else {
            return true;
        }
    }

    /**
     * 推送消息(请求百度推送)
     * @return bool
     */
    public function pushToApp()
    {
        if (Common::C('OpenPushToApp')) {

        } else {
            return true;
        }
    }

    /**
     * 开启日志
     * @param string $path 路径
     * @param string $data 数据
     * @return bool
     */
    public function saveLog($path = '', $data = '')
    {
        if (Common::C('openLog')) {
            file_put_contents($path, "执行时间：" . date('Y-m-d H:i:s') . " 数据：" . var_export($data, true) . "\n", FILE_APPEND);
        }
    }
}
