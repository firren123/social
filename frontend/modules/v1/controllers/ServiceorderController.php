<?php
/**
 * 服务订单
 *
 * PHP Version 5
 *
 * @category  Social
 * @package   Service
 * @author    linxinliang <linxinliang@iyangpin.com>
 * @time      2015/9/20
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      linxinliang@iyangpin.com
 */
namespace frontend\modules\v1\controllers;

use Yii;
use common\helpers\Common;
use common\helpers\RequestHelper;
use frontend\models\i500_social\Service;
use frontend\models\i500_social\ServiceTime;
use frontend\models\i500_social\ServiceOrder;
use frontend\models\i500_social\Order;
use frontend\models\i500_social\UserBasicInfo;

/**
 * Service time
 *
 * @category Social
 * @package  Servicetime
 * @author   linxinliang <linxinliang@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     linxinliang@iyangpin.com
 */
class ServiceorderController extends BaseController
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
     * 预约
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
        $data['service_id'] = RequestHelper::post('service_id', '', '');
        if (empty($data['service_id'])) {
            $this->returnJsonMsg('1010', [], Common::C('code', '1010'));
        }
        $data['appointment_service_time'] = RequestHelper::post('appointment_service_time', '', '');
        if (empty($data['appointment_service_time'])) {
            $this->returnJsonMsg('1031', [], Common::C('code', '1031'));
        }
        $data['appointment_service_address'] = RequestHelper::post('appointment_service_address', '', '');
        if (empty($data['appointment_service_address'])) {
            $this->returnJsonMsg('1032', [], Common::C('code', '1032'));
        }
        $data['source_type'] = RequestHelper::post('source_type', '', '');
        if (empty($data['source_type'])) {
            $this->returnJsonMsg('1033', [], Common::C('code', '1033'));
        }
        $data['remark'] = RequestHelper::post('remark', '', '');
        /**获取服务信息**/
        $service_model = new Service();
        $service_where['id']               = $data['service_id'];
        $service_where['audit_status']     = '2';
        $service_where['status']           = '1';
        $service_where['user_auth_status'] = '1';
        $service_where['is_deleted']       = '2';
        $service_fields = 'uid,mobile,image,title,price,unit,service_way,description';
        $service_info = $service_model->getInfo($service_where, true, $service_fields);
        if (empty($service_info)) {
            $this->returnJsonMsg('1011', [], Common::C('code', '1011'));
        }
        $order_model = new Order();
        //@todo 确定创建订单号为什么用省份？35=全国
        $data['order_sn']                 = $order_model->createSn('35', $data['mobile']);
        $data['service_uid']              = $service_info['uid'];
        $data['service_mobile']           = $service_info['mobile'];
        $data['service_way']              = $service_info['service_way'];
        $data['service_info_title']       = $service_info['title'];
        $data['service_info_image']       = $service_info['image'];
        $data['service_info_price']       = $service_info['price'];
        $data['service_info_unit']        = $service_info['unit'];
        //$data['service_info_description'] = $service_info['description'];
        $data['total']                    = $service_info['price'];
        $service_order_model = new ServiceOrder();
        $rs = $service_order_model->insertInfo($data);
        if (!$rs) {
            $this->returnJsonMsg('400', [], Common::C('code', '400'));
        }
        //@todo 预约成功后，更新商家当前时间段为不可预约状态
        $service_time_model = new ServiceTime();
        $day  = date('Y-m-d', strtotime($data['appointment_service_time']));
        $hour = date('H', strtotime($data['appointment_service_time']));
        if (!empty($day) && !empty($hour)) {
            $time_status = $service_time_model->updateTimeStatus($service_info['uid'], $day, $hour);
        }
        //@todo 应该用事务 判断这两个逻辑。
        $this->returnJsonMsg('200', [], Common::C('code', '200'));
    }

    /**
     * 预约列表
     * @return array
     */
    public function actionList()
    {
        $where['service_uid'] = RequestHelper::get('uid', '', '');
        if (empty($where['service_uid'])) {
            $this->returnJsonMsg('621', [], Common::C('code', '621'));
        }
        $where['service_mobile'] = RequestHelper::get('mobile', '', '');
        if (empty($where['service_mobile'])) {
            $this->returnJsonMsg('604', [], Common::C('code', '604'));
        }
        if (!Common::validateMobile($where['service_mobile'])) {
            $this->returnJsonMsg('605', [], Common::C('code', '605'));
        }
        $page      = RequestHelper::get('page', '1', 'intval');
        $page_size = RequestHelper::get('page_size', '6', 'intval');
        if ($page_size > Common::C('maxPageSize')) {
            $this->returnJsonMsg('705', [], Common::C('code', '705'));
        }
        $service_order_model = new ServiceOrder();
        $fields = 'service_info_title,mobile,appointment_service_time,appointment_service_address,status,pay_status,order_sn';
        $list = $service_order_model->getPageList($where, $fields, 'id desc', $page, $page_size);
        if (empty($list)) {
            $this->returnJsonMsg('1034', [], Common::C('code', '1034'));
        }
        $rs_info = [];
        foreach ($list as $k => $v) {
            $rs_info[$k]['day']        = date('Y-m-d', strtotime($v['appointment_service_time']));
            $rs_info[$k]['week']       = "周".Common::getWeek($rs_info[$k]['day']);
            $rs_info[$k]['hour']       = date('H', strtotime($v['appointment_service_time']));
            $rs_info[$k]['title']      = $v['service_info_title'];
            $rs_info[$k]['mobile']     = $v['mobile'];
            $rs_info[$k]['name']       = $this->_getUserInfo($v['mobile']);
            $rs_info[$k]['address']    = $v['appointment_service_address'];
            $rs_info[$k]['status']     = $v['status'];
            $rs_info[$k]['pay_status'] = $v['pay_status'];
            $rs_info[$k]['order_sn']   = $v['order_sn'];
        }
        $this->returnJsonMsg('200', $rs_info, Common::C('code', '200'));
    }

    /**
     * 订单详情
     * @return array
     */
    public function actionDetail()
    {

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
        $user_base_info_fields = 'nickname';
        $rs['nickname'] = '';
        $rs = $user_base_info_model->getInfo($user_base_info_where, true, $user_base_info_fields);
        return $rs['nickname'];
    }
}
