<?php
/**
 * 小工具
 *
 * PHP Version 5
 *
 * @category  Social
 * @package   Plug
 * @author    linxinliang <linxinliang@iyangpin.com>
 * @time      2015/8/05 09:21
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      linxinliang@iyangpin.com
 */

namespace frontend\modules\v1\controllers;

use frontend\models\i500_social\UserToken;
use frontend\models\i500_social\UserVerifyCode;
use Yii;
use common\helpers\RequestHelper;
use yii\web\Controller;

/**
 * Plug
 *
 * @category Social
 * @package  Plug
 * @author   linxinliang <linxinliang@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     linxinliang@iyangpin.com
 */
class PlugController extends Controller
{
    /**
     * 生成签名
     * @return string
     */
    public function actionSign()
    {
        $app_code = 'DKJA@(SL)RssMAKDKas!L';
        $timestamp = time();
        $val  = '';
        $params = $_GET;
        $data['msg'] = '';
        if ($params) {
            //ksort($params);
            foreach ($params as $k=>$v) {
                $val .= $v;
            }
            $sign = md5(md5(md5($app_code.$timestamp).md5($timestamp)).md5($val));
            $data['msg'] = '&amp;appId=I500_SOCIAL&amp;timestamp='.$timestamp.'&amp;sign='.$sign;
        }
        $data['timestamp'] = $timestamp;
        return $this->render('sign', ['data'=>$data]);
    }

    /**
     * 获取验证码
     * @return string
     */
    public function actionSms()
    {
        return $this->render('sms');
    }

    /**
     * 获取Token的方法
     * @return array
     */
    public function actionGetToken()
    {
        $mobile = RequestHelper::get('mobile', '', '');
        $user_token_model = new UserToken();
        $user_token_where['mobile'] = $mobile;
        $user_token_info = $user_token_model->getInfo($user_token_where);
        if (!empty($user_token_info)) {
            $msg = 'Token：'.$user_token_info['token'].' 创建时间：'.$user_token_info['create_time'];
        } else {
            $msg = '未能查询到数据。';
        }
        echo json_encode(['code'=>'ok','msg'=>$msg]);
    }

    /**
     * 获取验证码的方法
     * @return array
     */
    public function actionGetCode()
    {
        $mobile = RequestHelper::get('mobile', '', '');
        $user_code_model = new UserVerifyCode();
        $user_code_where['mobile'] = $mobile;
        $user_code_info = $user_code_model->getInfo($user_code_where, true, '*', '', 'id desc');
        if (!empty($user_code_info)) {
            $data['mobile']      = $user_code_info['mobile'];
            $data['code']        = $user_code_info['code'];
            $data['type']        = $user_code_info['type'];
            $data['create_time'] = $user_code_info['create_time'];
            $data['expires_in']  = $user_code_info['expires_in'];
        } else {
            $data['mobile'] = 'Error';
            $data['code'] = 'Error';
            $data['type'] = 'Error';
            $data['create_time'] = 'Error';
            $data['expires_in'] = 'Error';
        }
        echo json_encode(['code'=>'ok','data'=>$data]);
    }
}