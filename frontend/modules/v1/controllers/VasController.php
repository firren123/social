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
     * echo数组格式 array(code=0/1,data=>array,msg=>xxx)
     *   code 0=失败 1=成功
     *
     * @return void
     */
    public function actionQuery()
    {
        $helper_hxt = new HxtHelper();

        $arr = array();
        $arr['TerminalID'] = Yii::$app->params['hxt_TerminalID'];
        $arr['KeyID'] = Yii::$app->params['hxt_KeyID'];
        $arr['UserID'] = '';
        $arr['Account'] = '';
        $arr['EMail'] = '';
        $arr['CardNo'] = '';
        $arr['TotalFee'] = RequestHelper::post('totalfee', '0', 'intval');
        $arr['ShopCode'] = RequestHelper::post('shopcode', '', 'trim');
        //ShopCode  3102=抄表电用户信息查询 3202=智能电用户联机信息查询
        $arr['PaymentInfo'] = RequestHelper::post('paymentinfo', '', 'trim');
        //PaymentInfo  用户编号
        $arr['IPAddress'] = RequestHelper::post('ip', '127.0.0.1', 'trim');
        $arr['Source'] = '';
        $arr['TraceNo'] = '';

        $arr['MCode'] = $this->_createQueryMcode($arr);


        $arr_result = $helper_hxt->query($arr);
        if (isset($arr_result['PaymentOrderID'])) {
            unset($arr_result['PaymentOrderID']);
        }
        if (isset($arr_result['MCode'])) {
            unset($arr_result['MCode']);
        }
        //print_r($arr_result);

        echo json_encode($arr_result);
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
        $arr['TerminalID'] = Yii::$app->params['hxt_TerminalID'];
        $arr['KeyID'] = Yii::$app->params['hxt_KeyID'];
        $arr['UserID'] = '';
        $arr['Account'] = '';
        $arr['EMail'] = '';
        $arr['CardNo'] = '';
        $arr['SettlementDate'] = date("md", time());//银行清算日
        $arr['HostSerialNo'] = '999999999999';//银行扣费号
        $arr['TotalFee'] = RequestHelper::post('totalfee', '0', 'intval');
        $arr['ShopCode'] = RequestHelper::post('shopcode', '', 'trim');
        //ShopCode  3102=抄表电用户信息查询 3202=智能电用户联机信息查询
        $arr['PaymentInfo'] = RequestHelper::post('paymentinfo', '', 'trim');
        //PaymentInfo  用户编号
        $arr['IPAddress'] = RequestHelper::post('ip', '127.0.0.1', 'trim');
        $arr['Source'] = '';
        $arr['TraceNo'] = '';
        $arr['PromotionCode'] = '';


        $arr['MCode'] = $this->_createPayMcode($arr);

        $arr_result = $helper_hxt->pay($arr);
        if (isset($arr_result['PaymentOrderID'])) {
            unset($arr_result['PaymentOrderID']);
        }
        if (isset($arr_result['MCode'])) {
            unset($arr_result['MCode']);
        }
        //print_r($arr_result);

        echo json_encode($arr_result);
        return;
    }


    /**
     * 生成查询接口Mcode
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
        $mackey = Yii::$app->params['hxt_MacKey'];
        $mcode = md5($str1 . md5($mackey));
        return $mcode;
    }

    /**
     * 生成缴费接口Mcode
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
        $mackey = Yii::$app->params['hxt_MacKey'];
        $mcode = md5($str1 . md5($mackey));
        return $mcode;
    }

}
