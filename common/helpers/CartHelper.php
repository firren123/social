<?php
/**
 * 购物车相关逻辑
 *
 * PHP Version 5
 *
 * @category  Mall
 * @package   Helper
 * @author    linxinliang <linxinliang@iyangpin.com>
 * @time      15/6/4 下午3:33
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      linxinliang@iyangpin.com
 */
namespace common\helpers;

use Yii;
use common\helpers\Common;
use common\helpers\ActivityHelper;
use frontend\models\i500m\UserCart;
use frontend\models\i500m\Product;
use frontend\models\shop\ShopProducts;
use frontend\models\i500m\CrmActivity;

/**
 * Helper
 *
 * @category Mall
 * @package  Helper
 * @author   linxinliang <linxinliang@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     linxinliang@iyangpin.com
 */
class CartHelper
{
    /**
     * 加入购物车
     * @param int $user_id 用户ID
     * @param int $shop_id 店铺ID
     * @param int $goods_id 商品ID[标准库商品id]
     * @return array
     */
    public function addCart($user_id=0,$shop_id=0,$goods_id=0)
    {
        if (empty($user_id)) {
            return Common::statusInfo('1001');
        }
        if (empty($shop_id)) {
            return Common::statusInfo('2001');
        }
        if (empty($goods_id)) {
            return Common::statusInfo('3001');
        }
        /** 检测商品状态 **/
        $checkProductStatus = $this->_checkProductStatus($goods_id,$shop_id,true);
        if ($checkProductStatus['code']!='200') {
            return $checkProductStatus;
        }
        /** 判断购物车中是否存在这件商品 **/
        $m_cart = new UserCart();
        $cart_where = [];
        $cart_where['user_id'] = $user_id;
        $cart_where['shop_id'] = $shop_id;
        $cart_where['p_id']    = $goods_id;
        $cart_fields = 'id,activity_id,num';
        $cartInfo = $m_cart->getInfo($cart_where,true,$cart_fields);
        $activity_helper = new ActivityHelper();
        if (!empty($cartInfo)) {
            /** 表示 该购物车中已存在当前商品 **/
            $activityInfo = $activity_helper->checkGoodsJoinActivity($shop_id,$goods_id);
            if (!empty($cartInfo['activity_id'])) {
                /** 表示 原来商品参与了活动 **/
                if (!empty($activityInfo)) {
                    /** 表示该商品也正在参与活动 **/
                    /** 获取活动信息 **/
                    $crmActivityInfo = $activity_helper->getActivityInfo($activityInfo['activity_id']);
                    if (empty($crmActivityInfo)) {
                        return Common::statusInfo('4002');
                    }
                    /** 验证活动有效性 **/
                    $rs_check = $activity_helper->checkActivity($crmActivityInfo['type'],$activityInfo['activity_id'],$user_id,$goods_id);
                    if ($rs_check['code']=='200') {
                        /** 验证通过 **/
                        if ($cartInfo['activity_id'] != $activityInfo['activity_id']) {
                            /** 强制更新为当前活动 并且更新num=1 price=活动价格**/
                            /** 获取活动价格 **/
                            $activityProductInfo = $activity_helper->getActivityProductInfo($activityInfo['activity_id'],$goods_id);
                            if (!empty($activityProductInfo)) {
                                $cartData['num']            = '1';
                                $cartData['activity_id']    = $activityInfo['activity_id'];
                                $cartData['type']           = $crmActivityInfo['type'];
                                $cartData['activity_price'] = $activityProductInfo['price'];
                                $rs = $m_cart->updateInfo($cartData,['id'=>$cartInfo['id']]);
                                if ($rs) {
                                    return Common::statusInfo('200');
                                } else {
                                    return Common::statusInfo('5001');
                                }
                            } else {
                                return Common::statusInfo('4006');
                            }
                        } else {
                            /** 旧活动等于新活动 购物车数量+1**/
                            $cartData['num']  = $cartInfo['num']+1;
                            $cartData['type'] = $crmActivityInfo['type'];
                            $rs = $m_cart->updateInfo($cartData,['id'=>$cartInfo['id']]);
                            if ($rs) {
                                return Common::statusInfo('200');
                            } else {
                                return Common::statusInfo('5001');
                            }
                        }
                    } else {
                        /** 抛出错误 **/
                        return $rs_check;
                    }
                } else {
                    /** 表示该商品未参加活动 （强制更新当前商品未参加活动 num=1 price=正常价格）**/
                    $cartData['num']            = '1';
                    $cartData['type']           = '0';
                    $cartData['activity_id']    = '0';
                    $cartData['activity_price'] = '0.00';
                    $rs = $m_cart->updateInfo($cartData,['id'=>$cartInfo['id']]);
                    if ($rs) {
                        return Common::statusInfo('200');
                    } else {
                        return Common::statusInfo('5001');
                    }
                }
            } else {
                /** 表示 原来商品未参与活动 **/
                if (!empty($activityInfo)) {
                    /** 表示该商品正在参与活动 **/
                    /** 获取活动信息 **/
                    $crmActivityInfo = $activity_helper->getActivityInfo($activityInfo['activity_id']);
                    if (empty($crmActivityInfo)) {
                        return Common::statusInfo('4002');
                    }
                    $rs_check = $activity_helper->checkActivity($crmActivityInfo['type'],$activityInfo['activity_id'],$user_id,$goods_id);
                    if ($rs_check['code']=='200') {
                        /** 验证通过 **/
                        /** 强制更新为当前活动 并且更新num=1 price=活动价格 **/
                        /** 获取活动价格 **/
                        $activityProductInfo = $activity_helper->getActivityProductInfo($activityInfo['activity_id'],$goods_id);
                        if (!empty($activityProductInfo)) {
                            $cartData['num']            = '1';
                            $cartData['activity_id']    = $activityInfo['activity_id'];
                            $cartData['type']           = $crmActivityInfo['type'];
                            $cartData['activity_price'] = $activityProductInfo['price'];
                            $rs = $m_cart->updateInfo($cartData,['id'=>$cartInfo['id']]);
                            if ($rs) {
                                return Common::statusInfo('200');
                            } else {
                                return Common::statusInfo('5001');
                            }
                        } else {
                            return Common::statusInfo('4006');
                        }
                    } else {
                        /** 抛出错误 **/
                        return $rs_check;
                    }
                } else {
                    /** 表示该商品未参加活动 购物车数量+1 **/
                    $cartData['num']            = $cartInfo['num']+1;
                    $cartData['type']           = '0';
                    $cartData['activity_id']    = '0';
                    $cartData['activity_price'] = '0.00';
                    $rs = $m_cart->updateInfo($cartData,['id'=>$cartInfo['id']]);
                    if ($rs) {
                        return Common::statusInfo('200');
                    } else {
                        return Common::statusInfo('5001');
                    }
                }
            }
        } else {
            /** 表示 该商品未在购物车中 **/
            $activityInfo = $activity_helper->checkGoodsJoinActivity($shop_id,$goods_id);
            if (!empty($activityInfo)) {
                /** 表示参与活动 **/
                $crmActivityInfo = $activity_helper->getActivityInfo($activityInfo['activity_id']);
                if (empty($crmActivityInfo)) {
                    return Common::statusInfo('4002');
                }
                $rs_check = $activity_helper->checkActivity($crmActivityInfo['type'],$activityInfo['activity_id'],$user_id,$goods_id);
                if ($rs_check['code']=='200') {
                    /** 验证通过 **/
                    $activityProductInfo = $activity_helper->getActivityProductInfo($activityInfo['activity_id'],$goods_id);
                    if (!empty($activityProductInfo)) {
                        $addCartData['user_id']        = $user_id;
                        $addCartData['shop_id']        = $shop_id;
                        $addCartData['p_id']           = $goods_id;
                        $addCartData['num']            = '1';
                        $addCartData['create_time']    = date('Y-m-d H:i:s', time());
                        $addCartData['activity_id']    = $activityInfo['activity_id'];
                        $addCartData['type']           = $crmActivityInfo['type'];
                        $addCartData['activity_price'] = $activityProductInfo['price'];
                        $rs = $m_cart->insertInfo($addCartData);
                        if ($rs) {
                            return Common::statusInfo('200');
                        } else {
                            return Common::statusInfo('5002');
                        }
                    } else {
                        return Common::statusInfo('4006');
                    }

                } else {
                    /** 抛出错误 **/
                    return $rs_check;
                }
            } else {
                /** 表示未参加活动 **/
                $addCartData['user_id']     = $user_id;
                $addCartData['shop_id']     = $shop_id;
                $addCartData['p_id']        = $goods_id;
                $addCartData['num']         = '1';
                $addCartData['create_time'] = date('Y-m-d H:i:s', time());
                $rs = $m_cart->insertInfo($addCartData);
                if ($rs) {
                    return Common::statusInfo('200');
                } else {
                    return Common::statusInfo('5002');
                }
            }
        }
    }

    /**
     * 获取购物车列表
     * @param int $user_id 用户ID
     * @param int $shop_id 店铺ID
     * @return array
     */
    public function getCartList($user_id=0,$shop_id=0)
    {
        if (empty($user_id)) {
            return Common::statusInfo('1001');
        }
        if (empty($shop_id)) {
            return Common::statusInfo('2001');
        }
        $m_cart = new UserCart();
        $cart_where = [];
        $cart_where['user_id'] = $user_id;
        $cart_where['shop_id'] = $shop_id;
        $cart_fields = 'id,p_id,num,status,activity_id,type,activity_price';
        $cartInfo = $m_cart->getList($cart_where,$cart_fields);
        if (!empty($cartInfo)) {
            $activity_helper = new ActivityHelper();
            $rs = [];
            foreach($cartInfo as $k => $v) {
                /** 1. 检测商品状态 **/
                $checkProductStatus = $this->_checkProductStatus($v['p_id'],$shop_id,false);
                if ($checkProductStatus['code']!='200') {
                    $rs[$k]['product_tip'] = $checkProductStatus['code'];
                }
                /** 2. 检测活动信息 **/
                if (!empty($v['activity_id'])) {
                    $rs_check = $activity_helper->checkActivity($v['type'],$v['activity_id'],$user_id,$v['p_id']);
                    if ($rs_check['code']!='200') {
                        $rs[$k]['activity_tip'] = $rs_check['code'];
                    }
                }
                $rs[$k]['p_id']           = $v['p_id'];
                $rs[$k]['num']            = $v['num'];
                $rs[$k]['status']         = $v['status'];
                $rs[$k]['activity_id']    = $v['activity_id'];
                $rs[$k]['type']           = $v['type'];
                $rs[$k]['activity_price'] = $v['activity_price'];
                $rs[$k]['price']          = '0.00';
                $rs[$k]['image']          = Yii::$app->params['imgHost'].Yii::$app->params['defaultGoodsImg'];
                $rs[$k]['name']           = '';
                $rs[$k]['title']          = '';
                $rs[$k]['attr_value']     = '';
                /** 3. 查询shop_products.price**/
                $rs_shop_product = $this->_searchShopProducts($shop_id,$v['p_id'],'price');
                if (!empty($rs_shop_product)) {
                    $rs[$k]['price']      = $rs_shop_product['price'];
                }
                /** 4. 查询标准库的商品信息 **/
                $rs_product = $this->_search500mProduct($v['p_id']);
                if (!empty($rs_product)) {
                    if (!empty($rs_product['image'])) {
                        $rs[$k]['image']  = Yii::$app->params['imgHost'].$rs_product['image'];
                    }
                    $rs[$k]['name']       = $rs_product['name'];
                    $rs[$k]['title']      = $rs_product['title'];
                    $rs[$k]['attr_value'] = $rs_product['attr_value'];
                }
            }
            return $rs;
        } else {
            return Common::statusInfo('5003');
        }
    }

    /**
     * 编辑购物车数量
     * @param int $user_id  用户ID
     * @param int $shop_id  店铺ID
     * @param int $goods_id 商品ID
     * @param int $type     类别 1=加 2=减 3=更新为输入的值
     * @param int $num      输入的值
     * @return array
     */
    public function editCartNum($user_id=0,$shop_id=0,$goods_id=0,$type=0,$num=0)
    {
        if (!empty($user_id) && !empty($shop_id) && !empty($goods_id) && !empty($type)) {
            /** 1. 检测商品状态 **/
            $checkProductStatus = $this->_checkProductStatus($goods_id,$shop_id,false);
            if ($checkProductStatus['code']!='200') {
                return $checkProductStatus;
            }
            /** 2. 判断购物车中是否存在这件商品 **/
            $m_cart = new UserCart();
            $m_cart_where = [];
            $cart_where['user_id'] = $user_id;
            $cart_where['shop_id'] = $shop_id;
            $cart_where['p_id']    = $goods_id;
            $cart_fields = 'id,num,activity_id,type';
            $cartInfo = $m_cart->getInfo($cart_where,true,$cart_fields);
            if (empty($cartInfo)) {
                return Common::statusInfo('5005');
            }
            /** 3. 检测活动信息 **/
            if (!empty($cartInfo['activity_id'])) {
                $activity_helper = new ActivityHelper();
                $rs_check = $activity_helper->checkActivity($cartInfo['type'],$cartInfo['activity_id'],$user_id,$goods_id);
                if ($rs_check['code']!='200') {
                    return $rs_check;
                }
            }
            /** 4. 更新数据**/
            /** 获取当前库存 **/
            $shopProductInfo = $this->_searchShopProducts($shop_id,$goods_id,'product_number');
            $product_num = !empty($shopProductInfo['product_number']) ? $shopProductInfo['product_number'] : '0' ;
            $cartNum = $cartInfo['num'];
            $editNum = '1';
            if ($type=='1') {
                /** +1 操作 **/
                if ($cartNum + 1 > $product_num) {
                    return Common::statusInfo('3009');
                }
                $editNum = $cartNum+1;
            } elseif ($type=='2') {
                /** -1 操作 **/
                $editNum = $cartNum-1;
            } elseif ($type=='3') {
                /** 更新为输入的值 **/
                $num = intval($num);
                if ($cartNum + $num > $product_num) {
                    return Common::statusInfo('3009');
                }
                $editNum = $cartNum+$num;
            }
            $updateData = [];
            $updateData['num'] = $editNum;
            $update_cart_where = [];
            $update_cart_where['user_id'] = $user_id;
            $update_cart_where['shop_id'] = $shop_id;
            $update_cart_where['p_id']    = $goods_id;
            $rs_update = $m_cart->updateInfo($updateData,$update_cart_where);
            if ($rs_update) {
                return Common::statusInfo('200');
            } else {
                return Common::statusInfo('5006');
            }
        } else {
            return Common::statusInfo('5004');
        }
    }

    /**
     * 删除购物车方法
     * @param int $user_id  用户ID
     * @param int $shop_id  店铺ID
     * @param int $goods_id 商品ID
     * @return array
     */
    public function delCart($user_id=0,$shop_id=0,$goods_id=0)
    {
        /** 1. 判断购物车中是否存在这件商品 **/
        $m_cart = new UserCart();
        $m_cart_where = [];
        $cart_where['user_id'] = $user_id;
        $cart_where['shop_id'] = $shop_id;
        $cart_where['p_id']    = $goods_id;
        $cart_fields = 'id';
        $cartInfo = $m_cart->getInfo($cart_where,true,$cart_fields);
        if (empty($cartInfo)) {
            return Common::statusInfo('5005');
        }
        /** 2. 执行删除 **/
        $rs_del = $cartInfo->delete();
        if ($rs_del !== false) {
            return Common::statusInfo('200');
        } else {
            return Common::statusInfo('5007');
        }
    }

    /**
     * 检测商品状态
     * @param int  $goods_id         商品ID
     * @param int  $shop_id          店铺ID
     * @param bool $check_inventory  是否验证库存
     * @return array
     */
    private function _checkProductStatus($goods_id=0,$shop_id=0,$check_inventory=true)
    {
        /** 判断标准库中是否有这个商品 **/
        $m_product = new Product();
        $product_where = [];
        $product_where['id'] = $goods_id;
        $product_fields = 'id,verify_status,status';
        $product_info = $m_product->getInfo($product_where,true,$product_fields);
        if (empty($product_info)) {
            return Common::statusInfo('3002'); //不存在
        } else {
            if ($product_info['status']=='2') {
                return Common::statusInfo('3003'); //下架
            }
            if ($product_info['status']=='0') {
                return Common::statusInfo('3004'); //删除
            }
            if ($product_info['verify_status']!='1') {
                return Common::statusInfo('3005'); //未通过审核
            }
        }
        /** 判断商家商品表是否有这个商品 **/
        $m_shop_products = new ShopProducts();
        $shop_products_where = [];
        $shop_products_where['shop_id']    = $shop_id;
        $shop_products_where['product_id'] = $goods_id;
        $shop_products_fields = 'id,status,product_number';
        $shopProductsInfo = $m_shop_products->getInfo($shop_products_where,true,$shop_products_fields);
        if (empty($shopProductsInfo)) {
            return Common::statusInfo('3006');  //商家商品表中不存在
        } else {
            if ($shopProductsInfo['status']=='2') { //下架
                return Common::statusInfo('3007');
            }
            if ($shopProductsInfo['status']=='0') { //删除
                return Common::statusInfo('3008');
            }
            if ($check_inventory) {
                if ($shopProductsInfo['product_number'] < 1 ) { //库存不足
                    return Common::statusInfo('3009');
                }
            }
        }
        return Common::statusInfo('200');
    }

    /**
     * 查询shop.shop_products表中的数据
     * @param int $shop_id   店铺ID
     * @param int $goods_id  商品ID
     * @param string $fields 字段名称
     * @return array
     */
    private function _searchShopProducts($shop_id=0,$goods_id=0,$fields='')
    {
        $shopProductInfo = [];
        if (!empty($shop_id) && !empty($goods_id) && !empty($fields)) {
            $m_shop_product = new ShopProducts();
            $shop_product_where = [];
            $shop_product_where['shop_id']    = $shop_id;
            $shop_product_where['product_id'] = $goods_id;
            $shop_product_fields = $fields;
            $shopProductInfo = $m_shop_product->getInfo($shop_product_where,true,$shop_product_fields);
        }
        return $shopProductInfo;
    }

    /**
     * 查询500m.product表中的数据
     * @param int $goods_id 商品ID
     * @return array
     */
    private function _search500mProduct($goods_id=0)
    {
        $productInfo = [];
        if (!empty($goods_id)) {
            $m_product = new Product();
            $product_where = [];
            $product_where['id'] = $goods_id;
            $product_fields = 'image,name,title,attr_value';
            $productInfo = $m_product->getInfo($product_where,true,$product_fields);
        }
        return $productInfo;
    }
}