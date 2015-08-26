<?php
/**
 * 恒信通增值业务接口(value added service)
 *
 * PHP Version 5
 *
 * @category  SOCIAL
 * @package   CONTROLLER
 * @author    zhengyu <zhengyu@iyangpin.com>
 * @time      15/8/25 13:32
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      zhengyu@iyangpin.com
 */

namespace frontend\modules\v1\controllers;

use Yii;
use yii\web\Controller;
use common\helpers\HxtHelper;
use common\helpers\RequestHelper;


/**
 * 恒信通增值业务接口
 *
 * @category SOCIAL
 * @package  CONTROLLER
 * @author   zhengyu <zhengyu@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     zhengyu@iyangpin.com
 */
class VasController extends Controller
{

    /**
     * Action之前的处理
     *
     * Author zhengyu@iyangpin.com
     *
     * @param \yii\base\Action $action
     *
     * @return bool
     *
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }


    /**
     * 查询接口
     *
     * Author zhengyu@iyangpin.com
     *
     * @return void
     */
    public function actionQuery()
    {
        $helper_hxt = new HxtHelper();


        $terminalid = RequestHelper::post('terminalid', '', '');
        $keyid = RequestHelper::post('keyid', '', '');
        $userid = RequestHelper::post('userid', '', '');
        $account = RequestHelper::post('account', '', '');
        $email = RequestHelper::post('email', '', '');
        $cardno = RequestHelper::post('cardno', '', '');
        $totalfee = RequestHelper::post('totalfee', '', '');
        $shopcode = RequestHelper::post('shopcode', '', '');
        $paymentinfo = RequestHelper::post('paymentinfo', '', '');
        $ipaddress = RequestHelper::post('ipaddress', '', '');
        $source = RequestHelper::post('source', '', '');
        $traceno = RequestHelper::post('traceno', '', '');
        $mcode = RequestHelper::post('mcode', '', '');





        $helper_hxt->query();
        return;
    }


    /**
     * 缴费接口
     *
     * Author zhengyu@iyangpin.com
     *
     * @return void
     */
    public function actionPay()
    {

        return;
    }

}
