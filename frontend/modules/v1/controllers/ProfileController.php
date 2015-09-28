<?php
/**
 * 个人信息
 *
 * PHP Version 5
 *
 * @category  Social
 * @package   Profile
 * @author    linxinliang <linxinliang@iyangpin.com>
 * @time      2015/8/06
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      linxinliang@iyangpin.com
 */
namespace frontend\modules\v1\controllers;


use Yii;
use common\helpers\Common;
use common\helpers\RequestHelper;
use common\helpers\SsdbHelper;
use common\helpers\HuanXinHelper;
use frontend\models\i500_social\UserBasicInfo;
use frontend\models\i500_social\UserToken;
use frontend\models\i500_social\UserCoupons;
use frontend\models\i500_social\UserCommunity;
use frontend\models\i500_social\UserPushId;

/**
 * Profile
 *
 * @category Social
 * @package  Profile
 * @author   linxinliang <linxinliang@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     linxinliang@iyangpin.com
 */
class ProfileController extends BaseController
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
     * 个人信息
     * @return array
     */
    public function actionIndex()
    {
        $uid = RequestHelper::post('uid', '', '');
        if (empty($uid)) {
            $this->returnJsonMsg('621', [], Common::C('code', '621'));
        }
        $mobile = RequestHelper::post('mobile', '', '');
        if (empty($mobile)) {
            $this->returnJsonMsg('604', [], Common::C('code', '604'));
        }
        if (!Common::validateMobile($mobile)) {
            $this->returnJsonMsg('605', [], Common::C('code', '605'));
        }
        $user_mobile = RequestHelper::post('user_mobile', '', '');
        if (!empty($user_mobile)) {
            if (!Common::validateMobile($user_mobile)) {
                $this->returnJsonMsg('605', [], Common::C('code', '605'));
            }
            $mobile = $user_mobile;
        }
        $type = RequestHelper::post('type', '0', '');
        $user_base_model = new UserBasicInfo();
        $user_base_where['mobile'] = $mobile;
        $user_base_fields = 'id,mobile,nickname,avatar,personal_sign,realname,sex,birthday,province_id,city_id,district_id,community_name';
        if ($type == '1') {
            //仅获取昵称 + 头像
            $user_base_fields = 'nickname,avatar';
        }
        $user_base_info = $user_base_model->getInfo($user_base_where, true, $user_base_fields);
        if (empty($user_base_info)) {
            $user_base_data['uid']    = $uid;
            $user_base_data['mobile'] = $mobile;
            $rs = $user_base_model->insertInfo($user_base_data);
            if (!$rs) {
                $this->returnJsonMsg('400', [], Common::C('code', '400'));
            }
            $user_base_info = $user_base_model->getInfo($user_base_where, true, $user_base_fields);
        }
        if (!empty($user_base_info)) {
            if ($user_base_info['avatar']) {
                if (!strstr($user_base_info['avatar'], 'http')) {
                    $user_base_info['avatar'] = Common::C('imgHost').$user_base_info['avatar'];
                }
            } else {
                $user_base_info['avatar'] = Common::C('defaultAvatar');
            }
        }
        if ($type == '1') {
            //仅获取昵称 + 头像
            if (empty($user_base_info['nickname'])) {
                $user_base_info['nickname'] = Common::C('defaultNickName');
            }
        }
        $this->returnJsonMsg('200', $user_base_info, Common::C('code', '200'));
    }

    /**
     * 编辑
     * @return array
     */
    public function actionEdit()
    {
        $uid = RequestHelper::post('uid', '', '');
        if (empty($uid)) {
            $this->returnJsonMsg('621', [], Common::C('code', '621'));
        }
        $mobile = RequestHelper::post('mobile', '', '');
        if (empty($mobile)) {
            $this->returnJsonMsg('604', [], Common::C('code', '604'));
        }
        if (!Common::validateMobile($mobile)) {
            $this->returnJsonMsg('605', [], Common::C('code', '605'));
        }
        $user_base_update_data = [];
        $nickname = RequestHelper::post('nickname', '', 'trim');
        if (!empty($nickname)) {
            $user_base_update_data['nickname'] = $nickname;
        }
        $avatar = RequestHelper::post('avatar', '', 'trim');
        if (!empty($avatar)) {
            $user_base_update_data['avatar'] = $avatar;
        }
        $personal_sign = RequestHelper::post('personal_sign', '', 'trim');
        if (!empty($personal_sign)) {
            $user_base_update_data['personal_sign'] = $personal_sign;
        }
        $realname = RequestHelper::post('realname', '', 'trim');
        if (!empty($realname)) {
            $user_base_update_data['realname'] = $realname;
        }
        $sex = RequestHelper::post('sex', '', 'intval');
        if (!empty($sex)) {
            $user_base_update_data['sex'] = $sex;
        }
        $birthday = RequestHelper::post('birthday', '', 'trim');
        if (!empty($birthday)) {
            $user_base_update_data['birthday'] = $birthday;
        }
        $province_id = RequestHelper::post('province_id', '', 'intval');
        if (!empty($province_id)) {
            $user_base_update_data['province_id'] = $province_id;
        }
        $city_id = RequestHelper::post('city_id', '', 'intval');
        if (!empty($city_id)) {
            $user_base_update_data['city_id'] = $city_id;
        }
        $district_id = RequestHelper::post('district_id', '', 'intval');
        if (!empty($district_id)) {
            $user_base_update_data['district_id'] = $district_id;
        }
        $community_name = RequestHelper::post('community_name', '', 'trim');
        if (!empty($community_name)) {
            $user_base_update_data['community_name'] = $community_name;
        }
        $push_status = RequestHelper::post('push_status', '', 'intval');
        if (!empty($push_status)) {
            $user_base_update_data['push_status'] = $push_status;
        }
        //记录用户活跃时间
        $this->saveUserActiveTime(['mobile'=>$mobile]);
        if (!empty($user_base_update_data)) {
            $user_base_model = new UserBasicInfo();
            $user_base_where['mobile'] = $mobile;
            $rs = $user_base_model->updateInfo($user_base_update_data, $user_base_where);
            if (!$rs) {
                $this->returnJsonMsg('623', [], Common::C('code', '623'));
            } else {
                if (!empty($nickname)) {
                    HuanXinHelper::hxModifyNickName($mobile, $nickname);
                }
                $this->returnJsonMsg('200', [], Common::C('code', '200'));
            }
        } else {
            $this->returnJsonMsg('622', [], Common::C('code', '622'));
        }
    }

    /**
     * 设置小区
     * @return array
     */
    public function actionSetCommunity()
    {
        $uid = RequestHelper::post('uid', '', '');
        if (empty($uid)) {
            $this->returnJsonMsg('621', [], Common::C('code', '621'));
        }
        $mobile = RequestHelper::post('mobile', '', '');
        if (empty($mobile)) {
            $this->returnJsonMsg('604', [], Common::C('code', '604'));
        }
        if (!Common::validateMobile($mobile)) {
            $this->returnJsonMsg('605', [], Common::C('code', '605'));
        }
        $community_id = RequestHelper::post('community_id', '', 'intval');
        if (empty($community_id)) {
            $this->returnJsonMsg('642', [], Common::C('code', '642'));
        }
        $user_base_model = new UserBasicInfo();
        $user_base_where['mobile'] = $mobile;
        $user_base_fields = 'id,mobile';
        $user_base_info = $user_base_model->getInfo($user_base_where, true, $user_base_fields);
        if (empty($user_base_info)) {
            /**添加**/
            $user_base_data['uid']               = $uid;
            $user_base_data['mobile']            = $mobile;
            $user_base_data['last_community_id'] = $community_id;
            $rs = $user_base_model->insertInfo($user_base_data);
            if (!$rs) {
                $this->returnJsonMsg('400', [], Common::C('code', '400'));
            }
            $this->_checkUserCommunity($uid, $mobile, $community_id);
            $this->returnJsonMsg('200', [], Common::C('code', '200'));
        }
        /**编辑**/
        $user_base_update['last_community_id'] = $community_id;
        $update_rs = $user_base_model->updateInfo($user_base_update, $user_base_where);
        if (!$update_rs) {
            $this->returnJsonMsg('400', [], Common::C('code', '400'));
        }
        $this->_checkUserCommunity($uid, $mobile, $community_id);
        $this->returnJsonMsg('200', [], Common::C('code', '200'));
    }

    /**
     * 设置推送ID
     * @return array
     */
    public function actionSetPushId()
    {
        $uid = RequestHelper::post('uid', '', '');
        if (empty($uid)) {
            $this->returnJsonMsg('621', [], Common::C('code', '621'));
        }
        $mobile = RequestHelper::post('mobile', '', '');
        if (empty($mobile)) {
            $this->returnJsonMsg('604', [], Common::C('code', '604'));
        }
        if (!Common::validateMobile($mobile)) {
            $this->returnJsonMsg('605', [], Common::C('code', '605'));
        }
        $push_channel = RequestHelper::post('push_channel', '', 'intval');
        if (empty($push_channel)) {
            $this->returnJsonMsg('643', [], Common::C('code', '643'));
        }
        $push_id = RequestHelper::post('push_id', '', '');
        if (empty($push_id)) {
            $this->returnJsonMsg('644', [], Common::C('code', '644'));
        }
        $user_base_model = new UserBasicInfo();
        $user_base_where['mobile'] = $mobile;
        $user_base_fields = 'id,mobile';
        $user_base_info = $user_base_model->getInfo($user_base_where, true, $user_base_fields);
        if (empty($user_base_info)) {
            /**添加**/
            $user_base_data['uid']          = $uid;
            $user_base_data['mobile']       = $mobile;
            $user_base_data['last_push_id'] = $push_id;
            $rs = $user_base_model->insertInfo($user_base_data);
            if (!$rs) {
                $this->returnJsonMsg('400', [], Common::C('code', '400'));
            }
            $this->_setUserPushId($uid, $mobile, $push_channel, $push_id);
            $this->returnJsonMsg('200', [], Common::C('code', '200'));
        }
        /**编辑**/
        $user_base_update['last_push_id'] = $push_id;
        $update_rs = $user_base_model->updateInfo($user_base_update, $user_base_where);
        if (!$update_rs) {
            $this->returnJsonMsg('400', [], Common::C('code', '400'));
        }
        $this->_setUserPushId($uid, $mobile, $push_channel, $push_id);
        $this->returnJsonMsg('200', [], Common::C('code', '200'));
    }

    /**
     * 获取小区
     * @return array
     */
    public function actionGetCommunity()
    {
        $uid = RequestHelper::get('uid', '', '');
        if (empty($uid)) {
            $this->returnJsonMsg('621', [], Common::C('code', '621'));
        }
        $mobile = RequestHelper::get('mobile', '', '');
        if (empty($mobile)) {
            $this->returnJsonMsg('604', [], Common::C('code', '604'));
        }
        if (!Common::validateMobile($mobile)) {
            $this->returnJsonMsg('605', [], Common::C('code', '605'));
        }
        $rs['last_community_id'] = '0';
        $user_base_model = new UserBasicInfo();
        $user_base_where['mobile'] = $mobile;
        $user_base_fields = 'last_community_id';
        $user_base_info = $user_base_model->getInfo($user_base_where, true, $user_base_fields);
        if (empty($user_base_info)) {
            $this->returnJsonMsg('200', $rs, Common::C('code', '200'));
        }
        $this->returnJsonMsg('200', $user_base_info, Common::C('code', '200'));
    }

    /**
     * 退出登陆
     * @return array
     */
    public function actionLogOut()
    {
        $uid = RequestHelper::post('uid', '', '');
        if (empty($uid)) {
            $this->returnJsonMsg('621', [], Common::C('code', '621'));
        }
        $mobile = RequestHelper::post('mobile', '', '');
        if (empty($mobile)) {
            $this->returnJsonMsg('604', [], Common::C('code', '604'));
        }
        if (!Common::validateMobile($mobile)) {
            $this->returnJsonMsg('605', [], Common::C('code', '605'));
        }
        $user_token_model = new UserToken();
        $user_token_where['mobile'] = $mobile;
        $user_token_info = $user_token_model->getInfo($user_token_where, true, 'id');
        if (empty($user_token_info)) {
            $this->returnJsonMsg('627', [], Common::C('code', '627'));
        }
        $user_token_update['token'] = '';
        $rs = $user_token_model->updateInfo($user_token_update, $user_token_where);
        if (empty($rs)) {
            $this->returnJsonMsg('400', [], Common::C('code', '400'));
        }
        $this->returnJsonMsg('200', [], Common::C('code', '200'));
    }

    /**
     * 优惠券
     * @return array
     */
    public function actionCoupons()
    {
        $uid = RequestHelper::get('uid', '', '');
        if (empty($uid)) {
            $this->returnJsonMsg('621', [], Common::C('code', '621'));
        }
        $mobile = RequestHelper::get('mobile', '', '');
        if (empty($mobile)) {
            $this->returnJsonMsg('604', [], Common::C('code', '604'));
        }
        if (!Common::validateMobile($mobile)) {
            $this->returnJsonMsg('605', [], Common::C('code', '605'));
        }
        //get缓存
        $cache_key = 'coupons_'.$mobile;
        $cache_rs = SsdbHelper::Cache('get', $cache_key);
        if ($cache_rs) {
            $this->returnJsonMsg('200', $cache_rs, Common::C('code', '200'));
        }
        $user_coupons_model = new UserCoupons();
        $user_coupons_where['mobile'] = $mobile;
        $user_coupons_fields = '
        id,
        type_name as name,
        par_value as amount,
        get_time as start_time,
        expired_time as end_time,
        status,remark';
        $info = $user_coupons_model->getList($user_coupons_where, $user_coupons_fields, 'id desc');
        foreach ($info as $k => $v) {
            if (strtotime($v['end_time']) < time()) {
                $info[$k]['status'] = '2';
            }
        }
        //set 缓存
        SsdbHelper::Cache('set', $cache_key, $info, Common::C('SSDBCacheTime'));
        $this->returnJsonMsg('200', $info, Common::C('code', '200'));
    }

    /**
     * 验证token是否过期
     * @return array
     */
    public function actionCheckToken()
    {
        $uid = RequestHelper::post('uid', '', '');
        if (empty($uid)) {
            $this->returnJsonMsg('621', [], Common::C('code', '621'));
        }
        $mobile = RequestHelper::post('mobile', '', '');
        if (empty($mobile)) {
            $this->returnJsonMsg('604', [], Common::C('code', '604'));
        }
        if (!Common::validateMobile($mobile)) {
            $this->returnJsonMsg('605', [], Common::C('code', '605'));
        }
        $this->returnJsonMsg('200', [], Common::C('code', '200'));
    }

    /**
     * 验证并更新用户小区
     * @param int    $uid          用户ID
     * @param string $mobile       手机号
     * @param int    $community_id 小区ID
     * @return bool
     */
    private function _checkUserCommunity($uid = 0, $mobile = '', $community_id = 0)
    {
        if (!empty($community_id) && !empty($mobile) && !empty($uid)) {
            $user_community_model = new UserCommunity();
            $user_community_fields = 'id';
            $user_community_where['uid']          = $uid;
            $user_community_where['mobile']       = $mobile;
            $user_community_where['community_id'] = $community_id;
            $info = $user_community_model->getInfo($user_community_where, true, $user_community_fields);
            if (empty($info)) {
                /**执行添加**/
                $add_rs = $user_community_model->insertInfo($user_community_where);
                if (!$add_rs) {
                    return false;
                }
            }
        }
        return false;
    }

    /**
     * 设置用户推送ID
     * @param int    $uid          用户ID
     * @param string $mobile       手机号
     * @param int    $push_channel 推送平台
     * @param string $push_id      推送平台ID
     * @return bool
     */
    private function _setUserPushId($uid = 0, $mobile = '', $push_channel = 0, $push_id = '')
    {
        if (!empty($push_id) && !empty($push_channel) && !empty($mobile) && !empty($uid)) {
            $user_push_model = new UserPushId();
            $user_push_fields = 'id';

            $user_push_where['uid']      = $uid;
            $user_push_where['mobile']   = $mobile;
            $user_push_where['channel']  = $push_channel;
            $user_push_where['push_id']  = $push_id;
            $info = $user_push_model->getInfo($user_push_where, true, $user_push_fields);
            if (empty($info)) {
                /**执行添加**/
                $add_rs = $user_push_model->insertInfo($user_push_where);
                if (!$add_rs) {
                    return false;
                }
            }
        }
        return false;
    }
}
