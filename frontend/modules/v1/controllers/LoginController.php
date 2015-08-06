<?php
/**
 * 登陆
 *
 * PHP Version 5
 *
 * @category  Social
 * @package   Login
 * @author    linxinliang <linxinliang@iyangpin.com>
 * @time      2015/8/05 09:21
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      linxinliang@iyangpin.com
 */
namespace frontend\modules\v1\controllers;

use Yii;
use common\helpers\Common;
use common\helpers\RequestHelper;
use frontend\models\i500_social\User;
use frontend\models\i500_social\UserToken;
use frontend\models\i500_social\UserChannel;
use frontend\models\i500_social\UserSms;
use frontend\models\i500_social\UserVerifyCode;
use frontend\models\i500_social\LoginLog;

/**
 * Login
 *
 * @category Social
 * @package  Login
 * @author   linxinliang <linxinliang@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     linxinliang@iyangpin.com
 */
class LoginController extends BaseController
{
    /**
     * Before
     * @param \yii\base\Action $action Action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }
    /**
     * 登陆的方法
     *
     * Param int    $type     登陆方式
     * Param string $mobile   手机号
     * Param string $password md5后的密码
     * Param string $code     验证码
     *
     * @return array
     */
    public function actionIndex()
    {
        $type        = RequestHelper::post('type', '1', '');
        $channel     = RequestHelper::post('channel', '1', '');
        $source      = RequestHelper::post('dev', '1', '');
        $login_ua    = RequestHelper::post('login_ua', '', '');
        $mobile      = RequestHelper::post('mobile', '', 'trim');
        $password    = RequestHelper::post('password', '', 'trim');
        $code        = RequestHelper::post('code', '', 'trim');
        $first_login = RequestHelper::post('first_login', '2', '');
        if (empty($mobile)) {
            $this->returnJsonMsg('604', [], Common::C('code', '604'));
        }
        if (!Common::validateMobile($mobile)) {
            $this->returnJsonMsg('605', [], Common::C('code', '605'));
        }
        if ($channel != '1') {
            /**第三方合作平台登陆**/

        }
        if ($type == '1') {
            /**普通登陆**/
            if (empty($password)) {
                $this->returnJsonMsg('606', [], Common::C('code', '606'));
            }
            $user_model = new User();
            $user_where['mobile']     = $mobile;
            $user_where['is_deleted'] = '2';
            $user_fields = 'id,mobile,password,salt,login_count,status';
            $user_info = $user_model->getInfo($user_where, true, $user_fields);
            if (!empty($user_info)) {
                if ($user_info['status'] == '1') {
                    $this->returnJsonMsg('601', [], Common::C('code', '601'));
                }
                $password_1 = md5($user_info['salt'].$password);
                if ($password_1 != $user_info['password']) {
                    $this->returnJsonMsg('607', [], Common::C('code', '607'));
                }
            } else {
                $this->returnJsonMsg('602', [], Common::C('code', '602'));
            }
        } else {
            /**验证码登陆**/
            if (empty($code)) {
                $this->returnJsonMsg('608', [], Common::C('code', '608'));
            }
            $user_verify_code_model = new UserVerifyCode();
            $user_verify_code_where['mobile'] = $mobile;
            $user_verify_code_where['code']   = $code;
            $user_verify_code_where['type']   = '1';
            $user_verify_code_fields = 'id,expires_in';
            $user_verify_code_info = $user_verify_code_model->getInfo($user_verify_code_where, true, $user_verify_code_fields, '', 'id desc');
            if ($user_verify_code_info) {
                if (strtotime($user_verify_code_info['expires_in']) < time()) {
                    $this->returnJsonMsg('609', [], Common::C('code', '609'));
                }
            } else {
                $this->returnJsonMsg('610', [], Common::C('code', '610'));
            }
        }
        /**成功后记录日志**/
        $user_m = new User();
        $user_cond['mobile']     = $mobile;
        $user_cond['is_deleted'] = '2';
        $user_info = $user_m->getInfo($user_cond, true, 'id,login_count,salt');
        $password_random = Common::getRandomNumber();
        if ($first_login == '1') {
            $user_update_data['password']  = md5($user_info['salt'].md5($password_random));
        }
        $user_update_data['last_login_ip']      = Common::getIp();
        $user_update_data['last_login_channel'] = $channel;
        $user_update_data['last_login_source']  = $source;
        $user_update_data['login_count']        = $user_info['login_count'] + 1;
        $user_update_where['mobile'] = $mobile;
        $user_m->updateInfo($user_update_data, $user_update_where);

        $login_log_model = new LoginLog();
        $login_log_data['mobile']   = $mobile;
        $login_log_data['login_ip'] = Common::getIp();
        $login_log_data['channel']  = $channel;
        $login_log_data['source']   = $source;
        $login_log_data['login_ua'] = $login_ua;
        $login_log_model->insertInfo($login_log_data);

        $user_token_model = new UserToken();
        $user_token_where['mobile'] = $mobile;
        $user_token_fields = 'id,token';
        $user_token_info = $user_token_model->getInfo($user_token_where, true, $user_token_fields);
        if ($user_token_info) {
            /**更新token**/
            $user_token_data['token']       = md5($mobile.time());
            $user_token_data['create_time'] = date('Y-m-d H:i:s', time());
            $user_token_model->updateInfo($user_token_data, $user_token_where);
        } else {
            /**插入token**/
            $user_token_data['mobile'] = $mobile;
            $user_token_data['token']  = md5($mobile.time());
            $user_token_model->insertInfo($user_token_data);
        }

        if ($first_login == '1') {
            /**给用户发短信**/
            $user_sms_model = new UserSms();
            $user_sms_data['mobile']  = $mobile;
            $user_sms_data['content'] = Common::getSmsTemplate(2, $password_random);
            $user_sms_model->insertInfo($user_sms_data);
        }

        $rs_u_info['id']     = $user_info['id'];
        $rs_u_info['mobile'] = $mobile;
        $rs_u_info['token']  = $user_token_data['token'];
        $this->returnJsonMsg('200', $rs_u_info, Common::C('code', '200'));
    }

    /**
     * 发送验证码
     *
     * Param string $mobile   手机号
     *
     * @return array
     */
    public function actionSendcode()
    {
        $mobile = RequestHelper::post('mobile', '', '');
        if (empty($mobile)) {
            $this->returnJsonMsg('604', [], Common::C('code', '604'));
        }
        if (!Common::validateMobile($mobile)) {
            $this->returnJsonMsg('605', [], Common::C('code', '605'));
        }
        $user_model = new User();
        $user_where['mobile']     = $mobile;
        $user_where['is_deleted'] = '2';
        $user_fields = 'id,mobile,salt,status';
        $user_info = $user_model->getInfo($user_where, true, $user_fields);
        $first_login = 2;
        if (empty($user_info)) {
            /**未存在该用户**/
            $first_login = 1;
            $user_add_data['mobile'] = $mobile;
            $user_add_data['salt']   = Common::getRandomNumber();
            $rs = $user_model->insertInfo($user_add_data);
            if (!$rs) {
                $this->returnJsonMsg('400', [], Common::C('code', '400'));
            }
        }
        /**发送验证码**/
        $user_verify_code_model = new UserVerifyCode();
        $user_verify_code_data['mobile']     = $mobile;
        $user_verify_code_data['code']       = Common::getRandomNumber();
        $user_verify_code_data['type']       = '1';
        $user_verify_code_data['expires_in'] = date('Y-m-d H:i:s', (time()+ Common::C('verify_code_timeout')));
        $rs = $user_verify_code_model->insertInfo($user_verify_code_data);
        if (!$rs) {
            $this->returnJsonMsg('400', [], Common::C('code', '400'));
        }
        $user_sms_model = new UserSms();
        $user_sms_data['mobile']  = $mobile;
        $user_sms_data['content'] = Common::getSmsTemplate(1, $user_verify_code_data['code']);
        $rs = $user_sms_model->insertInfo($user_sms_data);
        if (!$rs) {
            $this->returnJsonMsg('611', [], Common::C('code', '611'));
        } else {
            $this->returnJsonMsg('200', ['first_login'=>$first_login], Common::C('code', '200'));
        }
    }
}
