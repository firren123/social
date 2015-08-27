<?php
/**
 * 商品
 *
 * PHP Version 5
 *
 * @category  Social
 * @package   Goods
 * @author    renyineng<renyineng@iyangpin.com>
 * @time      2015/8/25
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      renyineng@iyangpin.com
 */
namespace frontend\modules\v1\controllers;


use frontend\models\i500m\Brand;
use frontend\models\i500m\Category;
use frontend\models\i500m\Product;
use frontend\models\i500m\ProductImage;
use frontend\models\shop\ActivityGoods;
use frontend\models\shop\ShopActivity;
use frontend\models\shop\ShopProducts;
use Yii;
use common\helpers\Common;
use common\helpers\CurlHelper;
use common\helpers\SsdbHelper;
use common\helpers\RequestHelper;
use yii\helpers\ArrayHelper;

/**
 * Goods
 *
 * @category Social
 * @package  Shop
 * @author   linxinliang <renyineng@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     renyineng@iyangpin.com
 */
class GoodsController extends BaseController
{
    public $enableCsrfValidation = false;
    public $pageSize = 6;

    /**
     * 商品列表
     * @return json
     */
    public function actionIndex()
    {
        $cat_id = RequestHelper::get('cat_id', 0, 'intval');
        $this->shop_id = RequestHelper::get('shop_id', 0, 'intval');
        if (empty($cat_id)) {
            $this->returnJsonMsg(102, [], '分类id必须!');
        }
        if (empty($this->shop_id)) {
            $this->returnJsonMsg(103, [], '商家id无效!');
        }
        $shop_models = new ShopProducts();
        $map = ['shop_id'=>$this->shop_id, 'cat_id'=>$cat_id, 'status'=>1];
        $data_list = $shop_models->getGoodsList($map, 'product_id,price,product_number,status', $this->pageSize);
        //var_dump($data_list);exit();
        $goods_id = $goods_list = $goods = $data_goods = $shop_products = [];
        if (!empty($data_list['list'])) {
            foreach ($data_list['list'] as $k => $v) {
                $goods_id[] = $v['product_id'];
                //$shop_products[$v['product_id']] = $v;
            }
            //var_dump($shop_products);
            if (!empty($goods_id)) {
                $models = new Product();
                $goods = $models->getList(['id'=>$goods_id, 'status'=>1], 'id,name,image,bar_code,attr_value');
            }
            if (!empty($goods)) {
                foreach ($goods as $k => $v) {
                    $data_goods[$v['id']] = $v;
                }
            }
            //获取商家正在进行的限购的商品
            $a_model = new ShopActivity();
            $activity_goods = $a_model->getPurchaseGoods($this->shop_id, 3);

            $activity_goods = ArrayHelper::index($activity_goods, 'product_id');
            $img_path = Yii::$app->params['imgHost'];
            if (substr($img_path, -1) == '/') {
                $img_path = substr($img_path, 0, -1);
            }

            foreach ($data_list['list'] as $k => $v) {
                $data_list['list'][$k]['name'] = ArrayHelper::getValue($goods, $k.'.name', '');
                $data_list['list'][$k]['attr_value'] = ArrayHelper::getValue($goods, $k.'.attr_value', '');
                $data_list['list'][$k]['image'] = $img_path . ArrayHelper::getValue($goods, $k.'.image', '');
                $data_list['list'][$k]['purchase_num'] = ArrayHelper::getValue($activity_goods, $k.'.day_confine_num', 0);
                $data_list['list'][$k]['init_num'] = 0;

            }
            //var_dump($data_list);exit();
            $item = ArrayHelper::getValue($data_list, 'list', []);
            $pageCount = ArrayHelper::getValue($data_list, 'pageCount', 0);
            $count = ArrayHelper::getValue($data_list, 'count', 0);
            $this->returnJsonMsg(200, ['item'=>$item, 'pageCount'=>$pageCount, 'count'=>$count], '获取成功');

        } else {
            $this->returnJsonMsg(101, [], '无商品!');
        }

    }

    /**
     * 商品详情
     * @return array
     */
    public function actionDetail()
    {
        $product_id = RequestHelper::get('id', 0, 'intval');
        $this->shop_id = RequestHelper::get('shop_id', 0, 'intval');
        if (empty($product_id)) {
            $this->returnJsonMsg(101, [], '无效的商品id!');
        }
        if (empty($this->shop_id)) {
            $this->returnJsonMsg(102, [], '无效的商家id!');
        }
        $shop_model = new ShopProducts();
        $goods_info = $shop_model->getInfo(['shop_id'=>$this->shop_id, 'product_id'=>$product_id]);
        if ($goods_info['status'] != 1) {
            $this->returnJsonMsg(102, [], '此商品已经下架!');
        }
        $model = new Product();
        $info = $model->getInfo(['id'=>$product_id], 'name,description,status,attr_value');
        if ($info['status'] != 1) {
            $this->returnJsonMsg(102, [], '官方下架!');
        }
        $cat_model = new Category();
        //$cat_model->
        $cat_name = $cat_model->getField('name', ['id'=>$info['cate_first_id']]);
        $brand_model = new Brand();
        $brand_name = $brand_model->getField('name', ['id'=>$info['brand_id']]);
        $p_info = [
            'name'=>$info['name'],
            'price'=>$goods_info['price'],
            'cat_name'=>$cat_name,
            'brand'=>$brand_name,
            'activity_id'=>0,
            'subtitle'=>'',
            'activity_price'=>0,
            'purchase_num'=>0,
            'attribute'=>$info['attr_value'],
        ];
        $p_img = new ProductImage();
        $image_data = [];
        $image_list = $p_img->getList(['product_id'=>$product_id], 'image');
        $img_path = Yii::$app->params['imgHost'];
        if (substr($img_path, -1) == '/') {
            $img_path = substr($img_path, 0, -1);
        }
        if (!empty($image_list)) {
            foreach($image_list as $k => $v) {
                $image_data[] = $img_path . $v['image'];
            }
        }
        $activity = $shop_model->getActivity($this->shop_id, $product_id);
        if (!empty($activity)) {
            $p_info['activity_id'] = $activity['activity_id'];
            $p_info['activity_name'] = $activity['activity_id'];
            $p_info['subtitle'] = $activity['subtitle'];
            $p_info['activity_price'] = $activity['activity_price'];
            $p_info['purchase_num'] = $activity['purchase_num'];
        }
        $data = [
            'info'=> $p_info,
            'photo'=>$image_data,
        ];
        $this->returnJsonMsg(200, $data, 'SUCCESS');

    }

    /**
     * 获取热门推荐
     * @return json
     */
    public function actionHot()
    {
        $model = new Product();
        $this->shop_id = RequestHelper::get('shop_id', 0, 'intval');
        $list = $model->getList(['is_hot'=>1, 'single'=>1, 'status'=>1], 'id,name,image,attr_value');
        $new_list = $goods_list = [];
        if (!empty($list)) {
            $goods_id = [];
            foreach ($list as $k => $v) {
                $goods_id[] = $v['id'];
                $new_list[$v['id']] = $v;
            }
            $img_path = Yii::$app->params['imgHost'];
            if (substr($img_path, -1) == '/') {
                $img_path = substr($img_path, 0, -1);
            }
            if (!empty($goods_id)) {
                $shop_model = new ShopProducts();
                $goods_list = $shop_model->getList(['product_id'=>$goods_id, 'shop_id'=>$this->shop_id, 'status'=>1], 'product_id,product_number,price');
                if (!empty($goods_list)) {
                    foreach ($goods_list as $k => $v) {
                        $goods_list[$k]['name'] =  ArrayHelper::getValue($new_list, $v['product_id'].'.name', '');
                        $goods_list[$k]['attr_value'] = ArrayHelper::getValue($new_list, $v['product_id'].'.attr_value', '');
                        $goods_list[$k]['image'] = $img_path . ArrayHelper::getValue($new_list, $v['product_id'].'.image', '');
                        $goods_list[$k]['purchase_num'] = 0;
                        $goods_list[$k]['init_num'] = 0;
                    }
                }
            }
        }
        $this->returnJsonMsg(200, $goods_list, 'SUCCESS');
    }

    /**
     * 根据商家id 和活动类型 获取正在进行的所有活动
     * @return json
     */
    public function actionActivity()
    {
        $this->shop_id = RequestHelper::get('shop_id', 0, 'intval');
        $type = RequestHelper::get('type', 0, 'intval');
        $activity = new ShopActivity();
        if (empty($this->shop_id)) {
            $this->returnJsonMsg(101, [], '无效的商家id!');
        }
        if (empty($type)) {
            $this->returnJsonMsg(102, [], '无效的活动类型!');
        }
        $goods = $activity->getPurchaseGoods($this->shop_id, $type);
        $this->returnJsonMsg(200, $goods, 'SUCCESS');
       // var_dump($goods);
    }

    /**
     * Banner图片
     * @return json
     */
    public function actionBanner()
    {
        $this->shop_id = RequestHelper::get('shop_id', 0, 'intval');
        $model = new ShopActivity();
        $data = $model->getCurrentActivity($this->shop_id);
        $img_path = Yii::$app->params['imgHost'];
        if (substr($img_path, -1) == '/') {
            $img_path = substr($img_path, 0, -1);
        }
        $banner = [];
        foreach ($data as $k => $v) {
            $banner[$k] = [
                'image'=>$img_path . $v['images']
            ];
        }

        $this->returnJsonMsg(200, $banner, 'SUCCESS');
    }
}
