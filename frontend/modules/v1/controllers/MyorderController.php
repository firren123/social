<?php
/**
 * 我的订单
 *
 * PHP Version 5
 *
 * @category  Social
 * @package   Myorder
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
use frontend\models\i500_social\Order;
use frontend\models\i500_social\OrderDetail;

/**
 * 我的订单
 *
 * @category Social
 * @package  Myorder
 * @author   linxinliang <linxinliang@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     linxinliang@iyangpin.com
 */
class MyorderController extends BaseController
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
     * 我的订单列表
     * @return array
     */
    public function actionList()
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
        $shop_id = RequestHelper::get('shop_id', '', '');
        if (empty($shop_id)) {
            $this->returnJsonMsg('803', [], Common::C('code', '803'));
        }
        $order_status = RequestHelper::get('order_status', '1', '');
        if (!in_array($order_status, ['1','2','3','4'])) {
            $this->returnJsonMsg('804', [], Common::C('code', '804'));
        }
        $page      = RequestHelper::get('page', '1', 'intval');
        $page_size = RequestHelper::get('page_size', '6', 'intval');
        if ($page_size > Common::C('maxPageSize')) {
            $this->returnJsonMsg('705', [], Common::C('code', '705'));
        }
        //get缓存
        $cache_key = 'orders_'.$mobile.'_'.$shop_id.'_'.$order_status.'_'.$page;
        $cache_rs = SsdbHelper::Cache('get', $cache_key);
        if ($cache_rs) {
            $this->returnJsonMsg('200', $cache_rs, Common::C('code', '200'));
        }
        $info = [];
        if ($order_status != '4') {
            $order_model = new Order();
            $order_where['shop_id'] = $shop_id;
            $order_where['mobile']  = $mobile;
            $order_where['status']  = '1'; //已确认的订单
            $order_fields = 'order_sn,create_time,total';
            $order_and_where = '';
            if ($order_status == '1') {
                /**待支付**/
                $order_where['pay_status'] = '0';
            }
            if ($order_status == '2') {
                /**待收货**/
                $order_and_where = ['!=', 'ship_status' , '2'];
            }
            if ($order_status == '3') {
                /**已完成**/
                $order_where['ship_status'] = '2';
            }
            $info = $order_model->getPageList($order_where, $order_fields, 'id desc', $page, $page_size, $order_and_where);
            if (!empty($list)) {
                foreach ($list as $k => $v) {
                    $info[$k]['goods_info'] = $this->_getOrderGoodsInfo($mobile, $v['order_sn'], $shop_id);
                }
            }
        } else {
            /**退换货**/
        }
        //set 缓存
        SsdbHelper::Cache('set', $cache_key, $info, Common::C('SSDBCacheTime'));
        $this->returnJsonMsg('200', $info, Common::C('code', '200'));
    }

    /**
     * 订单评价
     * @return array
     */
    public function actionEvaluate()
    {

    }

    /**
     * 订单售后
     * @return array
     */
    public function actionAfterSales()
    {

    }

    /**
     * 获取订单的商品信息
     * @param string $mobile   手机号
     * @param string $order_sn 订单号
     * @param int    $shop_id  店铺ID
     * @return array
     */
    private function _getOrderGoodsInfo($mobile = '', $order_sn = '', $shop_id = 0)
    {
        if (empty($order_sn)) {
            $this->returnJsonMsg('805', [], Common::C('code', '805'));
        }
        $order_detail_model = new OrderDetail();
        $order_detail_where['mobile']   = $mobile;
        $order_detail_where['shop_id']  = $shop_id;
        $order_detail_where['order_sn'] = $order_sn;
        $order_detail_fields = 'product_id,product_name,product_img,num,price';
        $order_detail_info = $order_detail_model->getList($order_detail_where, $order_detail_fields, 'id desc');
        return $order_detail_info;
    }
}
