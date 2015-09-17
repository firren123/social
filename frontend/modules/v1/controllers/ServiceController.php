<?php
/**
 * 服务
 *
 * PHP Version 5
 *
 * @category  Social
 * @package   Service
 * @author    linxinliang <linxinliang@iyangpin.com>
 * @time      2015/9/14
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      linxinliang@iyangpin.com
 */
namespace frontend\modules\v1\controllers;

use Yii;
use common\helpers\Common;
use common\helpers\RequestHelper;
use frontend\models\i500_social\Service;
use frontend\models\i500_social\ServiceCategory;
use frontend\models\i500_social\ServiceSetting;
use frontend\models\i500_social\UserBasicInfo;

/**
 * Service
 *
 * @category Social
 * @package  Service
 * @author   linxinliang <linxinliang@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     linxinliang@iyangpin.com
 */
class ServiceController extends BaseController
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
     * 发布服务
     * @return array
     */
    public function actionAdd()
    {
        $data['uid'] = RequestHelper::post('uid', '', '');
        if (empty($data['uid'])) {
            $this->returnJsonMsg('621', [], Common::C('code', '621'));
        }
        $data['mobile'] = RequestHelper::post('mobile', '', '');
        if (empty($data['mobile'])) {
            $this->returnJsonMsg('604', [], Common::C('code', '604'));
        }
        if (!Common::validateMobile($data['mobile'])) {
            $this->returnJsonMsg('605', [], Common::C('code', '605'));
        }
        $data['category_id'] = RequestHelper::post('category_id', '0', 'intval');
        if (empty($data['category_id'])) {
            $this->returnJsonMsg('1000', [], Common::C('code', '1000'));
        }
        $data['son_category_id'] = RequestHelper::post('son_category_id', '0', 'intval');
        if (empty($data['son_category_id'])) {
            $this->returnJsonMsg('1001', [], Common::C('code', '1001'));
        }
        $data['image'] = RequestHelper::post('image', '', '');
        if (empty($data['image'])) {
            $this->returnJsonMsg('1002', [], Common::C('code', '1002'));
        }
        $data['title'] = RequestHelper::post('title', '', '');
        if (empty($data['title'])) {
            $this->returnJsonMsg('1003', [], Common::C('code', '1003'));
        }
        $data['price'] = RequestHelper::post('price', '', '');
        if (empty($data['price'])) {
            $this->returnJsonMsg('1004', [], Common::C('code', '1004'));
        }
        $data['unit'] = RequestHelper::post('unit', '0', 'intval');
        if (empty($data['unit'])) {
            $this->returnJsonMsg('1005', [], Common::C('code', '1005'));
        }
        $data['service_way'] = RequestHelper::post('service_way', '0', 'intval');
        if (empty($data['service_way'])) {
            $this->returnJsonMsg('1006', [], Common::C('code', '1006'));
        }
        $data['description'] = RequestHelper::post('description', '', '');
        if (empty($data['description'])) {
            $this->returnJsonMsg('1007', [], Common::C('code', '1007'));
        }
        /**查看该用户是否已经认证**/
        $audit_status = $this->_getSettingInfo($data['mobile'], 'audit_status');
        if ($audit_status == "") {
            $this->returnJsonMsg('1019', [], Common::C('code', '1019'));
        }
        if ($audit_status == '2') {
            /**user_auth_status=1表示认证成功**/
            $data['user_auth_status'] = '1';
        } else {
            /**user_auth_status=2表示认证失败**/
            $data['user_auth_status'] = '2';
        }
        $service_model = new Service();
        $rs = $service_model->insertInfo($data);
        if (!$rs) {
            $this->returnJsonMsg('400', [], Common::C('code', '400'));
        }
        $this->returnJsonMsg('200', [], Common::C('code', '200'));
    }

    /**
     * 编辑服务
     * @return array
     */
    public function actionEdit()
    {
        $data['uid'] = RequestHelper::post('uid', '', '');
        if (empty($data['uid'])) {
            $this->returnJsonMsg('621', [], Common::C('code', '621'));
        }
        $data['mobile'] = RequestHelper::post('mobile', '', '');
        if (empty($data['mobile'])) {
            $this->returnJsonMsg('604', [], Common::C('code', '604'));
        }
        if (!Common::validateMobile($data['mobile'])) {
            $this->returnJsonMsg('605', [], Common::C('code', '605'));
        }
        $where['id'] = RequestHelper::post('service_id', '0', 'intval');
        if (empty($where['id'])) {
            $this->returnJsonMsg('1010', [], Common::C('code', '1010'));
        }
        $where['mobile']       = $data['mobile'];
        $where['uid']          = $data['uid'];
        $where['is_deleted']   = '2';
        $data['category_id'] = RequestHelper::post('category_id', '0', 'intval');
        if (empty($data['category_id'])) {
            $this->returnJsonMsg('1000', [], Common::C('code', '1000'));
        }
        $data['son_category_id'] = RequestHelper::post('son_category_id', '0', 'intval');
        if (empty($data['son_category_id'])) {
            $this->returnJsonMsg('1001', [], Common::C('code', '1001'));
        }
        $data['image'] = RequestHelper::post('image', '', '');
        if (empty($data['image'])) {
            $this->returnJsonMsg('1002', [], Common::C('code', '1002'));
        }
        $data['title'] = RequestHelper::post('title', '', '');
        if (empty($data['title'])) {
            $this->returnJsonMsg('1003', [], Common::C('code', '1003'));
        }
        $data['price'] = RequestHelper::post('price', '', '');
        if (empty($data['price'])) {
            $this->returnJsonMsg('1004', [], Common::C('code', '1004'));
        }
        $data['unit'] = RequestHelper::post('unit', '0', 'intval');
        if (empty($data['unit'])) {
            $this->returnJsonMsg('1005', [], Common::C('code', '1005'));
        }
        $data['service_way'] = RequestHelper::post('service_way', '0', 'intval');
        if (empty($data['service_way'])) {
            $this->returnJsonMsg('1006', [], Common::C('code', '1006'));
        }
        $data['description'] = RequestHelper::post('description', '', '');
        if (empty($data['description'])) {
            $this->returnJsonMsg('1007', [], Common::C('code', '1007'));
        }
        $data['update_time'] = date('Y-m-d H:i:s', time());
        $service_model = new Service();
        $fields = 'id,audit_status';
        $info = $service_model->getInfo($where, true, $fields);
        if (empty($info)) {
            $this->returnJsonMsg('1011', [], Common::C('code', '1011'));
        }
        //审核状态 0=未审核1=审核中2=审核成功3=审核失败
        if ($info['audit_status'] == '1') {
            $this->returnJsonMsg('1020', [], Common::C('code', '1020'));
        }
        $data['audit_status'] = '0';
        /**查看该用户是否已经认证**/
        $audit_status = $this->_getSettingInfo($data['mobile'], 'audit_status');
        if ($audit_status == "") {
            $this->returnJsonMsg('1019', [], Common::C('code', '1019'));
        }
        if ($audit_status == '2') {
            /**user_auth_status=1表示认证成功**/
            $data['user_auth_status'] = '1';
        } else {
            /**user_auth_status=2表示认证失败**/
            $data['user_auth_status'] = '2';
        }
        $rs = $service_model->updateInfo($data, $where);
        if (!$rs) {
            $this->returnJsonMsg('400', [], Common::C('code', '400'));
        }
        $this->returnJsonMsg('200', [], Common::C('code', '200'));
    }

    /**
     * 服务详情
     * @return array
     */
    public function actionDetail()
    {
        $where['id'] = RequestHelper::get('service_id', '0', 'intval');
        if (empty($where['id'])) {
            $this->returnJsonMsg('1010', [], Common::C('code', '1010'));
        }
        $type = RequestHelper::get('type', '0', 'intval');
        if (empty($type)) {
            $this->returnJsonMsg('1008', [], Common::C('code', '1008'));
        }
        $fields = '*';
        if ($type == '1') {
            /**在首页或服务广场页查看服务详情**/
            $where['status']           = '1';
            $where['user_auth_status'] = '1';
            $where['audit_status']     = '2';
            $where['is_deleted']       = '2';
            $fields = 'id,category_id,son_category_id,image,title,price,unit,service_way,description';
        } elseif ($type =='2') {
            /**在我的服务中查看服务详情**/
            $where['uid'] = RequestHelper::get('uid', '', '');
            if (empty($where['uid'])) {
                $this->returnJsonMsg('621', [], Common::C('code', '621'));
            }
            $where['mobile'] = RequestHelper::get('mobile', '', '');
            if (empty($where['mobile'])) {
                $this->returnJsonMsg('604', [], Common::C('code', '604'));
            }
            if (!Common::validateMobile($where['mobile'])) {
                $this->returnJsonMsg('605', [], Common::C('code', '605'));
            }
            $where['is_deleted']   = '2';
            $fields = 'id,category_id,son_category_id,image,title,price,unit,service_way,description,status,audit_status';
        } else {
            $this->returnJsonMsg('1014', [], Common::C('code', '1014'));
        }
        $service_model = new Service();
        $info = $service_model->getInfo($where, true, $fields);
        if (empty($info)) {
            $this->returnJsonMsg('1011', [], Common::C('code', '1011'));
        }
        if ($info['image']) {
            $info['image'] = $this->_formatImg($info['image']);
        }
        $this->returnJsonMsg('200', $info, Common::C('code', '200'));
    }

    /**
     * 删除服务
     * @return array
     */
    public function actionDel()
    {
        $where['uid'] = RequestHelper::post('uid', '', '');
        if (empty($where['uid'])) {
            $this->returnJsonMsg('621', [], Common::C('code', '621'));
        }
        $where['mobile'] = RequestHelper::post('mobile', '', '');
        if (empty($where['mobile'])) {
            $this->returnJsonMsg('604', [], Common::C('code', '604'));
        }
        if (!Common::validateMobile($where['mobile'])) {
            $this->returnJsonMsg('605', [], Common::C('code', '605'));
        }
        $where['id'] = RequestHelper::post('service_id', '0', 'intval');
        if (empty($where['id'])) {
            $this->returnJsonMsg('1010', [], Common::C('code', '1010'));
        }
        $update_data['is_deleted'] = '1';
        $service_model = new Service();
        $rs = $service_model->updateInfo($update_data, $where);
        if (!$rs) {
            $this->returnJsonMsg('400', [], Common::C('code', '400'));
        }
        $this->returnJsonMsg('200', [], Common::C('code', '200'));
    }

    /**
     * 更新上下架
     * @return array
     */
    public function actionUpdateStatus()
    {
        $where['uid'] = RequestHelper::post('uid', '', '');
        if (empty($where['uid'])) {
            $this->returnJsonMsg('621', [], Common::C('code', '621'));
        }
        $where['mobile'] = RequestHelper::post('mobile', '', '');
        if (empty($where['mobile'])) {
            $this->returnJsonMsg('604', [], Common::C('code', '604'));
        }
        if (!Common::validateMobile($where['mobile'])) {
            $this->returnJsonMsg('605', [], Common::C('code', '605'));
        }
        $where['id'] = RequestHelper::post('service_id', '0', 'intval');
        if (empty($where['id'])) {
            $this->returnJsonMsg('1010', [], Common::C('code', '1010'));
        }
        $where['audit_status'] = '2';
        $where['is_deleted']   = '2';
        $update_data['status'] = RequestHelper::post('status', '0', 'intval');
        if (empty($update_data['status'])) {
            $this->returnJsonMsg('1012', [], Common::C('code', '1012'));
        }
        $service_model = new Service();
        $fields = 'id,status';
        $info = $service_model->getInfo($where, true, $fields);
        if (empty($info)) {
            $this->returnJsonMsg('1011', [], Common::C('code', '1011'));
        }
        if ($update_data['status'] == $info['status']) {
            $this->returnJsonMsg('1013', [], Common::C('code', '1013'));
        }
        $rs = $service_model->updateInfo($update_data, $where);
        if (!$rs) {
            $this->returnJsonMsg('400', [], Common::C('code', '400'));
        }
        $this->returnJsonMsg('200', [], Common::C('code', '200'));
    }
    /**
     * 获取首页服务
     * @return array
     */
    public function actionGetIndexService()
    {
        $page      = RequestHelper::get('page', '1', 'intval');
        $page_size = RequestHelper::get('page_size', '6', 'intval');
        if ($page_size > Common::C('maxPageSize')) {
            $this->returnJsonMsg('705', [], Common::C('code', '705'));
        }
        $service_model = new Service();
        $where['audit_status']     = '2';
        $where['user_auth_status'] = '1';
        $where['status']           = '1';
        $where['is_deleted']       = '2';
        $fields = 'id,mobile,image,title,price,unit,service_way';
        $list = $service_model->getPageList($where, $fields, 'id desc', $page, $page_size);
        if (empty($list)) {
            $this->returnJsonMsg('1009', [], Common::C('code', '1009'));
        }
        foreach ($list as $k => $v) {
            if ($v['image']) {
                $list[$k]['image'] = $this->_formatImg($v['image']);
            }
            if (!empty($v['mobile'])) {
                $user_info = $this->_getUserInfo($v['mobile']);
                $list[$k]['user_avatar']    = $user_info['avatar'];
                $list[$k]['search_address'] = $this->_getSettingInfo($v['mobile'], 'search_address');
                //@todo 距离需求请求仪能的接口
                $list[$k]['distance']       = '1.5公里';
            }
            unset($list[$k]['mobile']);
        }
        $this->returnJsonMsg('200', $list, Common::C('code', '200'));
    }

    /**
     * 获取服务广场
     * @return array
     */
    public function actionGetServiceSquare()
    {
        $type = RequestHelper::get('type', '0', 'intval');
        if (empty($type)) {
            $this->returnJsonMsg('1008', [], Common::C('code', '1008'));
        }
        $list = [];
        if ($type == '1') {
            /**附近**/
        } elseif ($type == '2') {
            /**分类**/
            $where['category_id']     = RequestHelper::get('category_id', '0', 'intval');
            $where['son_category_id'] = RequestHelper::get('son_category_id', '0', 'intval');
            if (empty($where['category_id'])) {
                $this->returnJsonMsg('1000', [], Common::C('code', '1000'));
            }
            if (empty($where['son_category_id'])) {
                unset($where['son_category_id']);
            }
            $page      = RequestHelper::get('page', '1', 'intval');
            $page_size = RequestHelper::get('page_size', '6', 'intval');
            if ($page_size > Common::C('maxPageSize')) {
                $this->returnJsonMsg('705', [], Common::C('code', '705'));
            }
            $service_model = new Service();
            $where['audit_status']     = '2';
            $where['user_auth_status'] = '1';
            $where['status']           = '1';
            $where['is_deleted']       = '2';
            $fields = 'id,mobile,image,title,price,unit,service_way';
            $list = $service_model->getPageList($where, $fields, 'id desc', $page, $page_size);
            if (empty($list)) {
                $this->returnJsonMsg('1009', [], Common::C('code', '1009'));
            }
            foreach ($list as $k => $v) {
                if ($v['image']) {
                    $list[$k]['image'] = $this->_formatImg($v['image']);
                }
                if (!empty($v['mobile'])) {
                    $user_info = $this->_getUserInfo($v['mobile']);
                    $list[$k]['user_avatar']    = $user_info['avatar'];
                    $list[$k]['search_address'] = $this->_getSettingInfo($v['mobile'], 'search_address');
                    //@todo 距离需求请求仪能的接口
                    $list[$k]['distance']       = '1.5公里';
                }
                unset($list[$k]['mobile']);
            }
        } else {
            $this->returnJsonMsg('1014', [], Common::C('code', '1014'));
        }
        $this->returnJsonMsg('200', $list, Common::C('code', '200'));
    }

    /**
     * 我的服务
     * @return array
     */
    public function actionMyService()
    {
        $where['uid'] = RequestHelper::get('uid', '', '');
        if (empty($where['uid'])) {
            $this->returnJsonMsg('621', [], Common::C('code', '621'));
        }
        $where['mobile'] = RequestHelper::get('mobile', '', '');
        if (empty($where['mobile'])) {
            $this->returnJsonMsg('604', [], Common::C('code', '604'));
        }
        if (!Common::validateMobile($where['mobile'])) {
            $this->returnJsonMsg('605', [], Common::C('code', '605'));
        }
        $page      = RequestHelper::get('page', '1', 'intval');
        $page_size = RequestHelper::get('page_size', '6', 'intval');
        if ($page_size > Common::C('maxPageSize')) {
            $this->returnJsonMsg('705', [], Common::C('code', '705'));
        }
        $service_model = new Service();
        $where['is_deleted']   = '2';
        $fields = 'id,mobile,image,title,price,unit,service_way,audit_status,status';
        $list = $service_model->getPageList($where, $fields, 'id desc', $page, $page_size);
        if (empty($list)) {
            $this->returnJsonMsg('1009', [], Common::C('code', '1009'));
        }
        foreach ($list as $k => $v) {
            if ($v['image']) {
                $list[$k]['image'] = $this->_formatImg($v['image']);
            }
            unset($list[$k]['mobile']);
        }
        $this->returnJsonMsg('200', $list, Common::C('code', '200'));
    }

    /**
     * 获取设置信息
     * @return array
     */
    public function actionGetSetting()
    {
        $where['uid'] = RequestHelper::get('uid', '', '');
        if (empty($where['uid'])) {
            $this->returnJsonMsg('621', [], Common::C('code', '621'));
        }
        $where['mobile'] = RequestHelper::get('mobile', '', '');
        if (empty($where['mobile'])) {
            $this->returnJsonMsg('604', [], Common::C('code', '604'));
        }
        if (!Common::validateMobile($where['mobile'])) {
            $this->returnJsonMsg('605', [], Common::C('code', '605'));
        }
        $fields = 'name,description,province_id,search_address,details_address,lng,lat,user_name,user_card,user_description,audit_status';
        $service_setting_model = new ServiceSetting();
        $info = $service_setting_model->getInfo($where, true, $fields);
        if (empty($info)) {
            $this->returnJsonMsg('1015', [], Common::C('code', '1015'));
        }
        //@todo 身份证号码需要处理，考虑是否需要返回
        $this->returnJsonMsg('200', $info, Common::C('code', '200'));
    }

    /**
     * 设置服务信息
     * @return array
     */
    public function actionSet()
    {
        $where['uid'] = RequestHelper::post('uid', '', '');
        if (empty($where['uid'])) {
            $this->returnJsonMsg('621', [], Common::C('code', '621'));
        }
        $where['mobile'] = RequestHelper::post('mobile', '', '');
        if (empty($where['mobile'])) {
            $this->returnJsonMsg('604', [], Common::C('code', '604'));
        }
        if (!Common::validateMobile($where['mobile'])) {
            $this->returnJsonMsg('605', [], Common::C('code', '605'));
        }
        $name = RequestHelper::post('name', '', '');
        $update_data = [];
        if (!empty($name)) {
            $update_data['name'] = $name;
        }
        $description = RequestHelper::post('description', '', '');
        if (!empty($description)) {
            $update_data['description'] = $description;
        }
        $province_id = RequestHelper::post('province_id', '', '');
        if (!empty($province_id)) {
            $update_data['province_id'] = $province_id;
        }
        $search_address = RequestHelper::post('search_address', '', '');
        if (!empty($search_address)) {
            $update_data['search_address'] = $search_address;
        }
        $details_address = RequestHelper::post('details_address', '', '');
        if (!empty($details_address)) {
            $update_data['details_address'] = $details_address;
        }
        $lng = RequestHelper::post('lng', '', '');
        if (!empty($lng)) {
            $update_data['lng'] = $lng;
        }
        $lat = RequestHelper::post('lat', '', '');
        if (!empty($lat)) {
            $update_data['lat'] = $lat;
        }
        $user_name = RequestHelper::post('user_name', '', '');
        if (!empty($user_name)) {
            $update_data['user_name'] = $user_name;
        }
        $user_card = RequestHelper::post('user_card', '', '');
        if (!empty($user_card)) {
            $update_data['user_card'] = $user_card;
            //验证身份证
            //@todo 通过身份证号未能获取到地址所在地
            if (strlen($update_data['user_card']) != '18') {
                $this->returnJsonMsg('1017', [], Common::C('code', '1017'));
            }
            if (!Common::isIdCard($update_data['user_card'])) {
                $this->returnJsonMsg('1018', [], Common::C('code', '1018'));
            }
            $update_data['user_age'] = Common::getAgeByCard($update_data['user_card']);
            $update_data['user_sex'] = Common::getSexByCard($update_data['user_card']);
        }
        $user_description = RequestHelper::post('user_description', '', '');
        if (!empty($user_description)) {
            $update_data['user_description'] = $user_description;
        }
        if (empty($update_data)) {
            $this->returnJsonMsg('1016', [], Common::C('code', '1016'));
        }
        $service_setting_model = new ServiceSetting();
        $info = $service_setting_model->getInfo($where, true, 'id,audit_status');
        if (empty($info)) {
            /**执行添加**/
            $update_data['uid']    = $where['uid'];
            $update_data['mobile'] = $where['mobile'];
            $rs = $service_setting_model->insertInfo($update_data);
        } else {
            if (!empty($user_name) || !empty($user_card) || !empty($user_description)) {
                //审核状态 0=未审核1=审核中2=审核成功3=审核失败
                if ($info['audit_status'] == '1') {
                    $this->returnJsonMsg('1021', [], Common::C('code', '1021'));
                }
                $update_data['audit_status'] = '0';
            }
            $update_data['update_time'] = date('Y-m-d H:i:s', time());
            /**执行更新**/
            $rs = $service_setting_model->updateInfo($update_data, $where);
        }
        if (!$rs) {
            $this->returnJsonMsg('400', [], Common::C('code', '400'));
        }
        $this->returnJsonMsg('200', [], Common::C('code', '200'));
    }

    /**
     * 获取服务分类
     * @return array
     */
    public function actionGetCategory()
    {
        $type = RequestHelper::get('type', '0', 'intval');
        if (empty($type)) {
            $this->returnJsonMsg('1008', [], Common::C('code', '1008'));
        }
        $service_category_model = new ServiceCategory();
        $where['pid']        = '0';
        $where['status']     = '2';
        $where['is_deleted'] = '2';
        $fields = 'id,name,image';
        $order  = 'sort desc';
        $info = $service_category_model->getList($where, $fields, $order);
        if (!empty($info)) {
            foreach ($info as $k => $v) {
                if ($v['image']) {
                    $info[$k]['image'] = $this->_formatImg($v['image']);
                }
                if ($type == '2') {
                    $info[$k]['son'] = $this->_getSonCategory($v['id']);
                }
                if ($type == '3') {
                    $info[$k]['son'] = $this->_getSonCategory($v['id'], '1');
                }
            }
        }
        $this->returnJsonMsg('200', $info, Common::C('code', '200'));
    }

    /**
     * 获取设置信息
     * @param string $mobile 手机号
     * @param string $params 参数名
     * @return string
     */
    private function _getSettingInfo($mobile = '',$params = '')
    {
        if (!empty($mobile) && !empty($params)) {
            $service_setting_model = new ServiceSetting();
            $where['mobile'] = $mobile;
            $fields = 'search_address,audit_status';
            $info = $service_setting_model->getInfo($where, true, $fields);
            if (!empty($info)) {
                return $info[$params];
            }
        }
        return '';
    }
    /**
     * 格式化图片
     * @param string $image 图片地址
     * @return string
     */
    private function _formatImg($image = '')
    {
        if (!empty($image)) {
            if (!strstr($image, 'http')) {
                return Common::C('imgHost').$image;
            }
        }
        return '';
    }

    /**
     * 获取子类的信息
     * @param int $pid  父类ID
     * @param int $type 类型ID 当type!=0的时候返回子类中返回"全部"
     * @return array
     */
    private function _getSonCategory($pid = 0, $type = 0)
    {
        $rs = [];
        if (!empty($type)) {
            $rs['id']    = '0';
            $rs['name']  = '全部';
            $rs['image'] = '';
        }
        if (!empty($pid)) {
            $service_category_model = new ServiceCategory();
            $fields = 'id,name,image';
            $where['pid']        = $pid;
            $where['status']     = '2';
            $where['is_deleted'] = '2';
            $order  = 'sort desc';
            $info = $service_category_model->getList($where, $fields, $order);
            if (!empty($type)) {
                array_unshift($info, $rs);//向数组插入元素
            }
            if (!empty($info)) {
                foreach ($info as $k => $v) {
                    if ($v['image']) {
                        $info[$k]['image'] = $this->_formatImg($v['image']);
                    }
                }
            }
            return $info;
        }
        return $rs;
    }

    /**
     * 获取用户信息
     * @param string $mobile 电话
     * @return array
     */
    private function _getUserInfo($mobile = '')
    {
        $user_base_info_model = new UserBasicInfo();
        $user_base_info_where['mobile'] = $mobile;
        $user_base_info_fields = 'avatar';
        $rs['avatar']   = '';
        $rs['nickname'] = '';
        $rs = $user_base_info_model->getInfo($user_base_info_where, true, $user_base_info_fields);
        if (!empty($rs)) {
            if ($rs['avatar']) {
                if (!strstr($rs['avatar'], 'http')) {
                    $rs['avatar'] = Common::C('imgHost').$rs['avatar'];
                }
            }
        }
        return $rs;
    }
}
