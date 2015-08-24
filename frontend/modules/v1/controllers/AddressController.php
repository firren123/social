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
use common\helpers\SsdbHelper;
use common\helpers\CurlHelper;
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
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    /**
     * 添加
     * @return array
     */
    public function actionAdd()
    {
        $uid = RequestHelper::post('uid', '', '');
        if (empty($uid)) {
            $this->returnJsonMsg('621', [], Common::C('code', '621'));
        }
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
        $data['search_address'] = RequestHelper::post('search_address', '', '');
        if (empty($data['search_address'])) {
            $this->returnJsonMsg('635', [], Common::C('code', '635'));
        }
        $data['details_address'] = RequestHelper::post('details_address', '', '');
        if (empty($data['details_address'])) {
            $this->returnJsonMsg('636', [], Common::C('code', '636'));
        }
        $data['is_default'] = RequestHelper::post('is_default', '0', '');
        $data['tag'] = RequestHelper::post('tag', '0', '');
        $data['lng'] = RequestHelper::post('lng', '0', '');
        $data['lat'] = RequestHelper::post('lat', '0', '');
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
        $uid = RequestHelper::post('uid', '', '');
        if (empty($uid)) {
            $this->returnJsonMsg('621', [], Common::C('code', '621'));
        }
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
        $data['search_address'] = RequestHelper::post('search_address', '', '');
        if (empty($data['search_address'])) {
            $this->returnJsonMsg('635', [], Common::C('code', '635'));
        }
        $data['details_address'] = RequestHelper::post('details_address', '', '');
        if (empty($data['details_address'])) {
            $this->returnJsonMsg('636', [], Common::C('code', '636'));
        }
        $data['is_default']  = RequestHelper::post('is_default', '0', '');
        $data['tag']         = RequestHelper::post('tag', '0', '');
        $data['lng']         = RequestHelper::post('lng', '0', '');
        $data['lat']         = RequestHelper::post('lat', '0', '');
        $data['update_time'] = date('Y-m-d H:i:s', time());
        $user_address_model = new UserAddress();
        $rs = $user_address_model->updateInfo($data, $where);
        if (empty($rs)) {
            $this->returnJsonMsg('400', [], Common::C('code', '400'));
        }
        //del缓存
        $cache_key = 'address_details_'.$where['id'].'_'.$data['mobile'];
        SsdbHelper::Cache('del', $cache_key);
        $this->returnJsonMsg('200', [], Common::C('code', '200'));
    }

    /**
     * 删除
     * @return array
     */
    public function actionDel()
    {
        $uid = RequestHelper::post('uid', '', '');
        if (empty($uid)) {
            $this->returnJsonMsg('621', [], Common::C('code', '621'));
        }
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
        //del缓存
        $cache_key = 'address_details_'.$where['id'].'_'.$where['mobile'];
        SsdbHelper::Cache('del', $cache_key);
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
        //get缓存
        $cache_key = 'address_list_'.$where['mobile'];
        $cache_rs = SsdbHelper::Cache('get', $cache_key);
        if ($cache_rs) {
            $this->returnJsonMsg('200', $cache_rs, Common::C('code', '200'));
        }
        $user_address_model  = new UserAddress();
        $user_address_fields = 'id,consignee,sex,consignee_mobile,province_id,search_address,details_address,is_default,tag';
        $list = $user_address_model->getList($where, $user_address_fields, 'id desc');
        //set缓存
        SsdbHelper::Cache('set', $cache_key, $list, Common::C('SSDBCacheTime'));
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
        //get缓存
        $cache_key = 'address_details_'.$where['id'].'_'.$where['mobile'];
        $cache_rs = SsdbHelper::Cache('get', $cache_key);
        if ($cache_rs) {
            $this->returnJsonMsg('200', $cache_rs, Common::C('code', '200'));
        }
        $user_address_model  = new UserAddress();
        $user_address_fields = 'id,consignee,sex,consignee_mobile,province_id,search_address,details_address,is_default,tag';
        $info = $user_address_model->getInfo($where, true, $user_address_fields);
        //set 缓存
        SsdbHelper::Cache('set', $cache_key, $info, Common::C('SSDBCacheTime'));
        $this->returnJsonMsg('200', $info, Common::C('code', '200'));
    }

    /**
     * 检索地址
     * @return array
     */
    public function actionSearch()
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
        $keywords = RequestHelper::get('keywords', '', '');
        if (empty($keywords)) {
            $this->returnJsonMsg('637', [], Common::C('code', '637'));
        }
        $url = Common::C('channelHost').'lbs/get-suggest?keywords='.$keywords;
        $res = CurlHelper::get($url);
        if ($res['code'] != '200' || empty($res['data'])) {
            $this->returnJsonMsg('200', [], Common::C('code', '200'));
        }
        $this->returnJsonMsg('200', $res['data'], Common::C('code', '200'));
    }
}
