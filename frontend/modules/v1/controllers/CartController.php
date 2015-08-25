<?php
/**
 * 购物车
 *
 * PHP Version 5
 *
 * @category  Social
 * @package   Cart
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
 * Cart
 *
 * @category Social
 * @package  Cart
 * @author   renyineng <renyineng@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     renyineng@iyangpin.com
 */
class CartController extends BaseController
{
    public $enableCsrfValidation = false;
    public function actionList()
    {
        $mobile = RequestHelper::get('mobile', 0, 'intval');
        $shop_id = RequestHelper::get('shop_id', 0, 'intval');
        if (empty($user_id) || empty($shop_id)) {
            $this->returnJsonMsg('101', [], '缺少参数');
        }
        $cart = new Cart();
        $cartList = $cart->getList(['mobile'=>$mobile, 'shop_id'=>$shop_id, 'status'=>1]);
        if (!empty($cartList)) {
            $product_ids = [];
            foreach ($cartList as $k=>$v) {
                $product_ids[] = $v['product_id'];
            }
            if (!empty($product_ids)) {
                $s_products = new ShopProducts();
                $goods_list = $s_products->getList(['product_id'=>$product_ids, 'shop_id'=>$shop_id], 'product_id');
                foreach ($goods_list as $k=>$v) {

                }
                $m_goods = new Product();
                $m_goods->getList(['id'=>$product_ids], 'id,');
            }

        }
    }
    /**
     * 添加购物车 {["product_id":"10","num":10],}
     * @return array
     */
    public function actionCheckCart()
    {
        $cart = RequestHelper::post('cart', '');
        $shop_id = RequestHelper::post('shop_id', 0, 'intval');

        $cart = htmlspecialchars_decode($cart);
        $list = json_decode($cart, true);
        $goods = [];
        if (!empty($list)) {
            $product_ids = $goods_lists = [];
            foreach ($list as $k=>$v) {
                $product_ids[] = $v['product_id'];
            }
            if (!empty($product_ids)) {
                $s_products = new ShopProducts();
                $map = ['product_id'=>$product_ids, 'shop_id'=>$shop_id];
                $goods_arr = $s_products->getList($map, 'product_id, product_number, status');

                foreach ($goods_arr as $k=>$v) {
                    $goods_lists[$v['product_id']] = $v;
                }
                foreach ($list as $k=>$v) {
                    $status = ArrayHelper::getValue($goods_lists, $v['product_id'].'.status', 0);
                    $goods[$k]['product_id'] = $v['product_id'];
                    $goods[$k]['message'] = '';
                    if ($status != 1) {
                        $goods[$k]['is_avail'] = 0;
                        $goods[$k]['message'] = '此商品已经下架';
                        continue;
                    }
                    $product_number = ArrayHelper::getValue($goods_lists, $v['product_id'].'.product_number', 0);
                    if ($v['num'] > $product_number) {
                        $goods[$k]['is_avail'] = 0;
                        $goods[$k]['message'] = '库存仅剩'.$product_number;
                    }
                }
            }
        }
        $this->returnJsonMsg(200, $goods, '');
    }
}
