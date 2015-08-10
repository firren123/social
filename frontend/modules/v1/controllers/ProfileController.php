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
use frontend\models\i500_social\UserBasicInfo;

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
        $uid = RequestHelper::post('uid', '', '');
        if (empty($uid)) {
            $this->returnJsonMsg('621', [], Common::C('code', '621'));
        }
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    /**
     * 个人信息
     * @return array
     */
    public function actionIndex()
    {
        $mobile = RequestHelper::post('mobile', '', '');
        if (empty($mobile)) {
            $this->returnJsonMsg('604', [], Common::C('code', '604'));
        }
        if (!Common::validateMobile($mobile)) {
            $this->returnJsonMsg('605', [], Common::C('code', '605'));
        }
        $user_base_model = new UserBasicInfo();
        $user_base_where['mobile'] = $mobile;
        $user_base_fields = 'mobile,nickname,avatar,personal_sign,realname,sex,birthday,province_id,city_id,district_id,community_name';
        $user_base_info = $user_base_model->getInfo($user_base_where, true, $user_base_fields);
        if (empty($user_base_info)) {
            $user_base_data['mobile'] = $mobile;
            $rs = $user_base_model->insertInfo($user_base_data);
            if (!$rs) {
                $this->returnJsonMsg('200', $user_base_info, Common::C('code', '200'));
            }
            $user_base_info = $user_base_model->getInfo($user_base_where, true, $user_base_fields);
        }
        if (!empty($user_base_info)) {
            if ($user_base_info['avatar']) {
                $user_base_info['avatar'] = Common::C('imgHost').$user_base_info['avatar'];
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
        $mobile = RequestHelper::post('mobile', '', '');
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
        if (!empty($user_base_update_data)) {
            $user_base_model = new UserBasicInfo();
            $user_base_where['mobile'] = $mobile;
            $rs = $user_base_model->updateInfo($user_base_update_data, $user_base_where);
            if (!$rs) {
                $this->returnJsonMsg('623', [], Common::C('code', '623'));
            } else {
                $this->returnJsonMsg('200', [], Common::C('code', '200'));
            }
        } else {
            $this->returnJsonMsg('622', [], Common::C('code', '622'));
        }
    }
}
