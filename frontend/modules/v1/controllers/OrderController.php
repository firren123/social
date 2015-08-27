<?php
/**
 * 订单
 *
 * PHP Version 5
 *
 * @category  Social
 * @package   Order
 * @author    renyineng <renyineng@iyangpin.com>
 * @time      2015/8/25
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      renyineng@iyangpin.com
 */
namespace frontend\modules\v1\controllers;


use frontend\models\i500_social\Cart;
use frontend\models\i500m\Product;
use frontend\models\shop\ShopProducts;
use Yii;
use common\helpers\Common;
use common\helpers\RequestHelper;
use yii\helpers\ArrayHelper;

/**
 * Order
 *
 * @category Social
 * @package  Order
 * @author   renyineng <linxinliang@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     renyineng@iyangpin.com
 */
class OrderController extends BaseController
{
    public $enableCsrfValidation = false;

    /**
     * 确认订单页面
     */
    public function actionConfirm()
    {
        //配送方式 商家配送
        //配送时间
        //配送地址
        //善品总价
        //配送费
        //优惠劵
        //商品清单
        //赠品
    }
    /**
     * 保存订单 {["product_id":"10","num":10],["product_id":"10","num":10]}
     */
    public function actionSave()
    {
        $cart_goods = RequestHelper::get('goods', '');
        $shop_id = RequestHelper::get('shop_id', 0, 'intval');
        $mobile = RequestHelper::get('mobile', '', '');
        $send_time = RequestHelper::post('send_time', '');
        $address_id = RequestHelper::post('address_id', 0, 'intval');
        $coupon_id = RequestHelper::post('coupon_id', '', 'intval');
        $pay_method_id = RequestHelper::post('pay_method_id', 0, 'intval');
        //$mobile = RequestHelper::post('mobile', '', '');

        if (empty($shop_id)) {
            $this->returnJsonMsg(101, [], '无效的商家id');
        }
        if (empty($cart_goods)) {
            $this->returnJsonMsg(102, [], '无效的数据');
        }
        if (empty($mobile)) {
            $this->returnJsonMsg(103, [], '无效的手机号');
        }
        $cart_goods = htmlspecialchars_decode($cart_goods);
        $list = json_decode($cart_goods, true);
        if (empty($list)) {
            $this->returnJsonMsg(104, [], '无效的json数据');
        }
        $goods = [];

        if (!empty($list)) {
            $product_ids = $goods_lists = [];
            foreach ($list as $k=>$v) {
                $product_ids[] = $v['product_id'];
            }
            if (!empty($product_ids)) {
                $s_products = new ShopProducts();
                $map = ['product_id'=>$product_ids, 'shop_id'=>$shop_id, 'status'=>1];
                $goods_arr = $s_products->getList($map, 'product_id, product_number, status');

                foreach ($list as $k=>$v) {
                    $goods_lists[$v['product_id']] = $v;
                }
                foreach ($goods_arr as $k=>$v) {
                    $product_ids[] = $v['product_id'];
                    $product_number = ArrayHelper::getValue($goods_lists, $v['product_id'].'.product_number', 0);
                    if ($v['product_number'] <= $product_number) {
                        $this->returnJsonMsg(105, [], '商品库存不足');
                    }
                }
                $p_model = new Product();
                $p_list = $p_model->getList(['id'=>$product_ids, 'status'=>1], 'id,name,image');
                var_dump($goods_arr);
                var_dump($p_list);
            }
            $this->returnJsonMsg(200, $goods, 'SUCCESS');
        } else {
            $this->returnJsonMsg(106, [], '购物车数据为空');
        }
    }
}
