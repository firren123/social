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
 * @link      linxinliang@iyangpin.com
 */
namespace frontend\modules\v1\controllers;


use Yii;
use common\helpers\Common;
use common\helpers\CurlHelper;
use common\helpers\SsdbHelper;
use common\helpers\RequestHelper;

/**
 * Cart
 *
 * @category Social
 * @package  Cart
 * @author   renyineng <renyineng@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     linxinliang@iyangpin.com
 */
class CartController extends BaseController
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
     * 添加购物车
     * @return array
     */
    public function actionAdd()
    {

    }
}
