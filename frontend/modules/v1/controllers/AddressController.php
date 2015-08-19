<?php
/**
 * 用户收货地址
 *
 * PHP Version 5
 *
 * @category  Social
 * @package   Address
 * @author    linxinliang <linxinliang@iyangpin.com>
 * @time      2015/8/19
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      linxinliang@iyangpin.com
 */
namespace frontend\modules\v1\controllers;

use Yii;
use common\helpers\Common;
use common\helpers\RequestHelper;
use frontend\models\i500_social\UserAddress;

/**
 * Address
 *
 * @category Social
 * @package  Address
 * @author   linxinliang <linxinliang@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     linxinliang@iyangpin.com
 */
class AddressController extends BaseController
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
     * 添加
     * @return array
     */
    public function actionAdd()
    {
        $data['mobile'] = RequestHelper::post('mobile', '', '');
        if (empty($data['mobile'])) {
            $this->returnJsonMsg('604', [], Common::C('code', '604'));
        }
        if (!Common::validateMobile($data['mobile'])) {
            $this->returnJsonMsg('605', [], Common::C('code', '605'));
        }
        $data['consignee'] = RequestHelper::post('consignee', '', '');
        if (empty($data['consignee'])) {
            $this->returnJsonMsg('628', [], Common::C('code', '628'));
        }
        $data['consignee_mobile'] = RequestHelper::post('consignee_mobile', '', '');
        if (empty($data['consignee_mobile'])) {
            $this->returnJsonMsg('629', [], Common::C('code', '629'));
        }
        $data['sex'] = RequestHelper::post('sex', '0', '');
        if (empty($data['sex'])) {
            $this->returnJsonMsg('630', [], Common::C('code', '630'));
        }
        $data['province_id'] = RequestHelper::post('province_id', '', '');
        if (empty($data['province_id'])) {
            $this->returnJsonMsg('631', [], Common::C('code', '631'));
        }
        $data['city_id'] = RequestHelper::post('city_id', '', '');
        if (empty($data['city_id'])) {
            $this->returnJsonMsg('632', [], Common::C('code', '632'));
        }
        $data['district_id'] = RequestHelper::post('district_id', '', '');
        if (empty($data['district_id'])) {
            $this->returnJsonMsg('633', [], Common::C('code', '633'));
        }
        $data['address'] = RequestHelper::post('address', '', '');
        if (empty($data['district_id'])) {
            $this->returnJsonMsg('633', [], Common::C('code', '633'));
        }
        $data['is_default'] = RequestHelper::post('is_default', '0', '');
        $data['tag'] = RequestHelper::post('tag', '0', '');
        $user_address_model = new UserAddress();
        $rs = $user_address_model->insertInfo($data);
        if (empty($rs)) {
            $this->returnJsonMsg('400', [], Common::C('code', '400'));
        }
        $this->returnJsonMsg('200', [], Common::C('code', '200'));
    }

    /**
     * 编辑
     * @return array
     */
    public function actionEdit()
    {
        $data['mobile'] = RequestHelper::post('mobile', '', '');
        if (empty($data['mobile'])) {
            $this->returnJsonMsg('604', [], Common::C('code', '604'));
        }
        if (!Common::validateMobile($data['mobile'])) {
            $this->returnJsonMsg('605', [], Common::C('code', '605'));
        }
        $where['id'] = RequestHelper::post('address_id', '', '');
        if (empty($where['id'])) {
            $this->returnJsonMsg('634', [], Common::C('code', '634'));
        }
        $data['consignee'] = RequestHelper::post('consignee', '', '');
        if (empty($data['consignee'])) {
            $this->returnJsonMsg('628', [], Common::C('code', '628'));
        }
        $data['consignee_mobile'] = RequestHelper::post('consignee_mobile', '', '');
        if (empty($data['consignee_mobile'])) {
            $this->returnJsonMsg('629', [], Common::C('code', '629'));
        }
        $data['sex'] = RequestHelper::post('sex', '0', '');
        if (empty($data['sex'])) {
            $this->returnJsonMsg('630', [], Common::C('code', '630'));
        }
        $data['province_id'] = RequestHelper::post('province_id', '', '');
        if (empty($data['province_id'])) {
            $this->returnJsonMsg('631', [], Common::C('code', '631'));
        }
        $data['city_id'] = RequestHelper::post('city_id', '', '');
        if (empty($data['city_id'])) {
            $this->returnJsonMsg('632', [], Common::C('code', '632'));
        }
        $data['district_id'] = RequestHelper::post('district_id', '', '');
        if (empty($data['district_id'])) {
            $this->returnJsonMsg('633', [], Common::C('code', '633'));
        }
        $data['address'] = RequestHelper::post('address', '', '');
        if (empty($data['district_id'])) {
            $this->returnJsonMsg('633', [], Common::C('code', '633'));
        }
        $data['is_default']  = RequestHelper::post('is_default', '0', '');
        $data['tag']         = RequestHelper::post('tag', '0', '');
        $data['update_time'] = date('Y-m-d H:i:s', time());
        $user_address_model = new UserAddress();
        $rs = $user_address_model->updateInfo($data, $where);
        if (empty($rs)) {
            $this->returnJsonMsg('400', [], Common::C('code', '400'));
        }
        $this->returnJsonMsg('200', [], Common::C('code', '200'));
    }

    /**
     * 删除
     * @return array
     */
    public function actionDel()
    {
        $where['mobile'] = RequestHelper::post('mobile', '', '');
        if (empty($where['mobile'])) {
            $this->returnJsonMsg('604', [], Common::C('code', '604'));
        }
        if (!Common::validateMobile($where['mobile'])) {
            $this->returnJsonMsg('605', [], Common::C('code', '605'));
        }
        $where['id'] = RequestHelper::post('address_id', '', '');
        if (empty($where['id'])) {
            $this->returnJsonMsg('634', [], Common::C('code', '634'));
        }
        $data['is_deleted'] = '1';
        $user_address_model = new UserAddress();
        $rs = $user_address_model->updateInfo($data, $where);
        if (empty($rs)) {
            $this->returnJsonMsg('400', [], Common::C('code', '400'));
        }
        $this->returnJsonMsg('200', [], Common::C('code', '200'));
    }

    /**
     * 列表
     * @return array
     */
    public function actionList()
    {
        $uid = RequestHelper::get('uid', '', '');
        if (empty($uid)) {
            $this->returnJsonMsg('621', [], Common::C('code', '621'));
        }
        $where['mobile'] = RequestHelper::get('mobile', '', '');
        if (empty($where['mobile'])) {
            $this->returnJsonMsg('604', [], Common::C('code', '604'));
        }
        if (!Common::validateMobile($where['mobile'])) {
            $this->returnJsonMsg('605', [], Common::C('code', '605'));
        }
        $where['is_deleted'] = '2';
        $user_address_model  = new UserAddress();
        $user_address_fields = 'id,consignee,sex,consignee_mobile,province_id,city_id,district_id,address,is_default,tag';
        $list = $user_address_model->getList($where, $user_address_fields, 'id desc');
        $this->returnJsonMsg('200', $list, Common::C('code', '200'));
    }

    /**
     * 获取详情
     * @return array
     */
    public function actionDetails()
    {
        $uid = RequestHelper::get('uid', '', '');
        if (empty($uid)) {
            $this->returnJsonMsg('621', [], Common::C('code', '621'));
        }
        $where['mobile'] = RequestHelper::get('mobile', '', '');
        if (empty($where['mobile'])) {
            $this->returnJsonMsg('604', [], Common::C('code', '604'));
        }
        if (!Common::validateMobile($where['mobile'])) {
            $this->returnJsonMsg('605', [], Common::C('code', '605'));
        }
        $where['id'] = RequestHelper::post('address_id', '', '');
        if (empty($where['id'])) {
            $this->returnJsonMsg('634', [], Common::C('code', '634'));
        }
        $where['is_deleted'] = '2';
        $user_address_model  = new UserAddress();
        $user_address_fields = 'id,consignee,sex,consignee_mobile,province_id,city_id,district_id,address,is_default,tag';
        $list = $user_address_model->getInfo($where, true, $user_address_fields);
        $this->returnJsonMsg('200', $list, Common::C('code', '200'));
    }
}
