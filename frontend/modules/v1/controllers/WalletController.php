<?php
/**
 * 钱包
 *
 * PHP Version 5
 *
 * @category  Social
 * @package   Wallet
 * @author    linxinliang <linxinliang@iyangpin.com>
 * @time      2015/9/22
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      linxinliang@iyangpin.com
 */
namespace frontend\modules\v1\controllers;

use Yii;
use common\helpers\Common;
use common\helpers\RequestHelper;
use frontend\models\i500_social\UserWallet;
use frontend\models\i500_social\UserCoupons;

/**
 * Wallet
 *
 * @category Social
 * @package  Wallet
 * @author   linxinliang <linxinliang@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     linxinliang@iyangpin.com
 */
class WalletController extends BaseController
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
     * 获取钱包
     * @return array
     */
    public function actionGet()
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
        $wallet_model = new UserWallet();
        $wallet_info = $wallet_model->getInfo($where, true, 'money,integral');
        if (empty($wallet_info)) {
            /**新增**/
            $wallet_add_data['uid']    = $where['uid'];
            $wallet_add_data['mobile'] = $where['mobile'];
            $add_rs = $wallet_model->insertInfo($wallet_add_data);
            if (!$add_rs) {
                $this->returnJsonMsg('400', [], Common::C('code', '400'));
            }
            $wallet_info['money']    = '0.00';
            $wallet_info['integral'] = '0';
        }
        $coupon_model = new UserCoupons();
        $coupon_where['mobile'] = $where['mobile'];
        $coupon_where['status'] = '0';
        $wallet_info['coupon_count'] = $coupon_model->getCount($coupon_where);
        $this->returnJsonMsg('200', $wallet_info, Common::C('code', '200'));
    }
}
