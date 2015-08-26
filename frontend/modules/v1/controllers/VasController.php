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

        $arr = array();
        $arr['TerminalID'] = RequestHelper::post('terminalid', '', '');
        $arr['KeyID'] = RequestHelper::post('keyid', '', '');
        $arr['UserID'] = RequestHelper::post('userid', '', '');
        $arr['Account'] = RequestHelper::post('account', '', '');
        $arr['EMail'] = RequestHelper::post('email', '', '');
        $arr['CardNo'] = RequestHelper::post('cardno', '', '');
        $arr['TotalFee'] = RequestHelper::post('totalfee', '', '');
        $arr['ShopCode'] = RequestHelper::post('shopcode', '', '');
        $arr['PaymentInfo'] = RequestHelper::post('paymentinfo', '', '');
        $arr['IPAddress'] = RequestHelper::post('ipaddress', '', '');
        $arr['Source'] = RequestHelper::post('source', '', '');
        $arr['TraceNo'] = RequestHelper::post('traceno', '', '');

        $arr['MCode'] = $this->_createQueryMcode($arr);


        $helper_hxt->query($arr);
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
        $helper_hxt = new HxtHelper();

        $arr = array();


        $arr['MCode'] = $this->_createQueryMcode($arr);


        $helper_hxt->query($arr);
        return;
    }


    /**
     * 生成Mcode
     *
     * Author zhengyu@iyangpin.com
     *
     * @param array $arr 参数
     *
     * @return string 返回生成的签名
     */
    private function _createQueryMcode($arr)
    {
        $str1 = $arr['TerminalID'] . $arr['KeyID'] . $arr['UserID'] . $arr['Account'] . $arr['EMail']
            . $arr['CardNo'] . $arr['TotalFee'] . $arr['ShopCode'] . $arr['PaymentInfo'] . $arr['IPAddress']
            . $arr['Source'] . $arr['TraceNo'];
        $mackey = '';
        $mcode = md5($str1 . md5($mackey));
        return $mcode;
    }

    /**
     * 生成Mcode
     *
     * Author zhengyu@iyangpin.com
     *
     * @param array $arr 参数
     *
     * @return string 返回生成的签名
     */
    private function _createPayMcode($arr)
    {
        $str1 = $arr['TerminalID'] . $arr['KeyID'] . $arr['UserID'] . $arr['Account']
            . $arr['EMail'] . $arr['CardNo'] . $arr['SettlementDate'] . $arr['HostSerialNo']
            . $arr['TotalFee'] . $arr['ShopCode'] . $arr['PaymentInfo'] . $arr['IPAddress']
            . $arr['Source'] . $arr['TraceNo']. $arr['PromotionCode'];
        $mackey = '';
        $mcode = md5($str1 . md5($mackey));
        return $mcode;
    }

}
