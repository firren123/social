<?php
/**
 * 服务
 *
 * PHP Version 5
 *
 * @category  Social
 * @package   Service
 * @author    linxinliang <linxinliang@iyangpin.com>
 * @time      2015/9/14
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      linxinliang@iyangpin.com
 */
namespace frontend\modules\v1\controllers;

use Yii;
use common\helpers\Common;
use common\helpers\RequestHelper;
use frontend\models\i500_social\Service;
use frontend\models\i500_social\ServiceCategory;
use frontend\models\i500_social\UserBasicInfo;

/**
 * Service
 *
 * @category Social
 * @package  Service
 * @author   linxinliang <linxinliang@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     linxinliang@iyangpin.com
 */
class ServiceController extends BaseController
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
     * 发布服务
     * @return array
     */
    public function actionAdd()
    {
        $data['uid'] = RequestHelper::post('uid', '', '');
        if (empty($data['uid'])) {
            $this->returnJsonMsg('621', [], Common::C('code', '621'));
        }
        $data['mobile'] = RequestHelper::post('mobile', '', '');
        if (empty($data['mobile'])) {
            $this->returnJsonMsg('604', [], Common::C('code', '604'));
        }
        if (!Common::validateMobile($data['mobile'])) {
            $this->returnJsonMsg('605', [], Common::C('code', '605'));
        }
        $data['category_id'] = RequestHelper::post('category_id', '0', 'intval');
        if (empty($data['category_id'])) {
            $this->returnJsonMsg('1000', [], Common::C('code', '1000'));
        }
        $data['son_category_id'] = RequestHelper::post('son_category_id', '0', 'intval');
        if (empty($data['son_category_id'])) {
            $this->returnJsonMsg('1001', [], Common::C('code', '1001'));
        }
        $data['image'] = RequestHelper::post('image', '', '');
        if (empty($data['image'])) {
            $this->returnJsonMsg('1002', [], Common::C('code', '1002'));
        }
        $data['title'] = RequestHelper::post('title', '', '');
        if (empty($data['title'])) {
            $this->returnJsonMsg('1003', [], Common::C('code', '1003'));
        }
        $data['price'] = RequestHelper::post('price', '', '');
        if (empty($data['price'])) {
            $this->returnJsonMsg('1004', [], Common::C('code', '1004'));
        }
        $data['unit'] = RequestHelper::post('unit', '0', 'intval');
        if (empty($data['unit'])) {
            $this->returnJsonMsg('1005', [], Common::C('code', '1005'));
        }
        $data['service_way'] = RequestHelper::post('service_way', '0', 'intval');
        if (empty($data['service_way'])) {
            $this->returnJsonMsg('1006', [], Common::C('code', '1006'));
        }
        $data['description'] = RequestHelper::post('description', '', '');
        if (empty($data['description'])) {
            $this->returnJsonMsg('1007', [], Common::C('code', '1007'));
        }
        $service_model = new Service();
        $rs = $service_model->insertInfo($data);
        if (!$rs) {
            $this->returnJsonMsg('400', [], Common::C('code', '400'));
        }
        $this->returnJsonMsg('200', [], Common::C('code', '200'));
    }

    /**
     * 编辑服务
     * @return array
     */
    public function actionEdit()
    {
        $data['uid'] = RequestHelper::post('uid', '', '');
        if (empty($data['uid'])) {
            $this->returnJsonMsg('621', [], Common::C('code', '621'));
        }
        $data['mobile'] = RequestHelper::post('mobile', '', '');
        if (empty($data['mobile'])) {
            $this->returnJsonMsg('604', [], Common::C('code', '604'));
        }
        if (!Common::validateMobile($data['mobile'])) {
            $this->returnJsonMsg('605', [], Common::C('code', '605'));
        }
        $where['id'] = RequestHelper::post('service_id', '0', 'intval');
        if (empty($where['id'])) {
            $this->returnJsonMsg('1010', [], Common::C('code', '1010'));
        }
        $where['mobile']       = $data['mobile'];
        $where['uid']          = $data['uid'];
        $where['is_deleted']   = '2';
        $data['category_id'] = RequestHelper::post('category_id', '0', 'intval');
        if (empty($data['category_id'])) {
            $this->returnJsonMsg('1000', [], Common::C('code', '1000'));
        }
        $data['son_category_id'] = RequestHelper::post('son_category_id', '0', 'intval');
        if (empty($data['son_category_id'])) {
            $this->returnJsonMsg('1001', [], Common::C('code', '1001'));
        }
        $data['image'] = RequestHelper::post('image', '', '');
        if (empty($data['image'])) {
            $this->returnJsonMsg('1002', [], Common::C('code', '1002'));
        }
        $data['title'] = RequestHelper::post('title', '', '');
        if (empty($data['title'])) {
            $this->returnJsonMsg('1003', [], Common::C('code', '1003'));
        }
        $data['price'] = RequestHelper::post('price', '', '');
        if (empty($data['price'])) {
            $this->returnJsonMsg('1004', [], Common::C('code', '1004'));
        }
        $data['unit'] = RequestHelper::post('unit', '0', 'intval');
        if (empty($data['unit'])) {
            $this->returnJsonMsg('1005', [], Common::C('code', '1005'));
        }
        $data['service_way'] = RequestHelper::post('service_way', '0', 'intval');
        if (empty($data['service_way'])) {
            $this->returnJsonMsg('1006', [], Common::C('code', '1006'));
        }
        $data['description'] = RequestHelper::post('description', '', '');
        if (empty($data['description'])) {
            $this->returnJsonMsg('1007', [], Common::C('code', '1007'));
        }
        $data['update_time'] = date('Y-m-d H:i:s', time());
        $service_model = new Service();
        $fields = 'id';
        $info = $service_model->getInfo($where, true, $fields);
        if (empty($info)) {
            $this->returnJsonMsg('1011', [], Common::C('code', '1011'));
        }
        $rs = $service_model->updateInfo($data, $where);
        if (!$rs) {
            $this->returnJsonMsg('400', [], Common::C('code', '400'));
        }
        $this->returnJsonMsg('200', [], Common::C('code', '200'));
    }

    /**
     * 服务详情
     * @return array
     */
    public function actionDetail()
    {
        $where['id'] = RequestHelper::get('service_id', '0', 'intval');
        if (empty($where['id'])) {
            $this->returnJsonMsg('1010', [], Common::C('code', '1010'));
        }
        $type = RequestHelper::get('type', '0', 'intval');
        if (empty($type)) {
            $this->returnJsonMsg('1008', [], Common::C('code', '1008'));
        }
        $fields = '*';
        if ($type == '1') {
            /**在首页或服务广场页查看服务详情**/
            $where['status']       = '1';
            $where['audit_status'] = '2';
            $where['is_deleted']   = '2';
            $fields = 'id,category_id,son_category_id,image,title,price,unit,service_way,description';
        } elseif ($type =='2') {
            /**在我的服务中查看服务详情**/
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
            $where['is_deleted']   = '2';
            $fields = 'id,category_id,son_category_id,image,title,price,unit,service_way,description,status,audit_status';
        } else {
            $this->returnJsonMsg('1014', [], Common::C('code', '1014'));
        }
        $service_model = new Service();
        $info = $service_model->getInfo($where, true, $fields);
        if (empty($info)) {
            $this->returnJsonMsg('1011', [], Common::C('code', '1011'));
        }
        if ($info['image']) {
            $info['image'] = $this->_formatImg($info['image']);
        }
        $this->returnJsonMsg('200', $info, Common::C('code', '200'));
    }

    /**
     * 删除服务
     * @return array
     */
    public function actionDel()
    {
        $where['uid'] = RequestHelper::post('uid', '', '');
        if (empty($where['uid'])) {
            $this->returnJsonMsg('621', [], Common::C('code', '621'));
        }
        $where['mobile'] = RequestHelper::post('mobile', '', '');
        if (empty($where['mobile'])) {
            $this->returnJsonMsg('604', [], Common::C('code', '604'));
        }
        if (!Common::validateMobile($where['mobile'])) {
            $this->returnJsonMsg('605', [], Common::C('code', '605'));
        }
        $where['id'] = RequestHelper::post('service_id', '0', 'intval');
        if (empty($where['id'])) {
            $this->returnJsonMsg('1010', [], Common::C('code', '1010'));
        }
        $update_data['is_deleted'] = '1';
        $service_model = new Service();
        $rs = $service_model->updateInfo($update_data, $where);
        if (!$rs) {
            $this->returnJsonMsg('400', [], Common::C('code', '400'));
        }
        $this->returnJsonMsg('200', [], Common::C('code', '200'));
    }

    /**
     * 更新上下架
     * @return array
     */
    public function actionUpdateStatus()
    {
        $where['uid'] = RequestHelper::post('uid', '', '');
        if (empty($where['uid'])) {
            $this->returnJsonMsg('621', [], Common::C('code', '621'));
        }
        $where['mobile'] = RequestHelper::post('mobile', '', '');
        if (empty($where['mobile'])) {
            $this->returnJsonMsg('604', [], Common::C('code', '604'));
        }
        if (!Common::validateMobile($where['mobile'])) {
            $this->returnJsonMsg('605', [], Common::C('code', '605'));
        }
        $where['id'] = RequestHelper::post('service_id', '0', 'intval');
        if (empty($where['id'])) {
            $this->returnJsonMsg('1010', [], Common::C('code', '1010'));
        }
        $where['audit_status'] = '2';
        $where['is_deleted']   = '2';
        $update_data['status'] = RequestHelper::post('status', '0', 'intval');
        if (empty($update_data['status'])) {
            $this->returnJsonMsg('1012', [], Common::C('code', '1012'));
        }
        $service_model = new Service();
        $fields = 'id,status';
        $info = $service_model->getInfo($where, true, $fields);
        if (empty($info)) {
            $this->returnJsonMsg('1011', [], Common::C('code', '1011'));
        }
        if ($update_data['status'] == $info['status']) {
            $this->returnJsonMsg('1013', [], Common::C('code', '1013'));
        }
        $rs = $service_model->updateInfo($update_data, $where);
        if (!$rs) {
            $this->returnJsonMsg('400', [], Common::C('code', '400'));
        }
        $this->returnJsonMsg('200', [], Common::C('code', '200'));
    }
    /**
     * 获取首页服务
     * @return array
     */
    public function actionGetIndexService()
    {
        $page      = RequestHelper::get('page', '1', 'intval');
        $page_size = RequestHelper::get('page_size', '6', 'intval');
        if ($page_size > Common::C('maxPageSize')) {
            $this->returnJsonMsg('705', [], Common::C('code', '705'));
        }
        $service_model = new Service();
        $where['audit_status'] = '2';
        $where['status']       = '1';
        $where['is_deleted']   = '2';
        $fields = 'id,mobile,category_id,son_category_id,image,title,price,unit,service_way,description';
        $list = $service_model->getPageList($where, $fields, 'id desc', $page, $page_size);
        if (empty($list)) {
            $this->returnJsonMsg('1009', [], Common::C('code', '1009'));
        }
        foreach ($list as $k => $v) {
            if ($v['image']) {
                $list[$k]['image'] = $this->_formatImg($v['image']);
            }
            if (!empty($v['mobile'])) {
                $user_info = $this->_getUserInfo($v['mobile']);
                $list[$k]['user_avatar'] = $user_info['avatar'];
            }
            unset($list[$k]['mobile']);
        }
        $this->returnJsonMsg('200', $list, Common::C('code', '200'));
    }

    /**
     * 获取服务广场
     * @return array
     */
    public function actionGetServiceSquare()
    {
        $type = RequestHelper::get('type', '0', 'intval');
        if (empty($type)) {
            $this->returnJsonMsg('1008', [], Common::C('code', '1008'));
        }
        $list = [];
        if ($type == '1') {
            /**附近**/
        } elseif ($type == '2') {
            /**分类**/
            $where['category_id'] = RequestHelper::get('category_id', '0', 'intval');
            if (empty($where['category_id'])) {
                $this->returnJsonMsg('1000', [], Common::C('code', '1000'));
            }
            $page      = RequestHelper::get('page', '1', 'intval');
            $page_size = RequestHelper::get('page_size', '6', 'intval');
            if ($page_size > Common::C('maxPageSize')) {
                $this->returnJsonMsg('705', [], Common::C('code', '705'));
            }
            $service_model = new Service();
            $where['audit_status'] = '2';
            $where['status']       = '1';
            $where['is_deleted']   = '2';
            $fields = 'id,mobile,category_id,son_category_id,image,title,price,unit,service_way,description';
            $list = $service_model->getPageList($where, $fields, 'id desc', $page, $page_size);
            if (empty($list)) {
                $this->returnJsonMsg('1009', [], Common::C('code', '1009'));
            }
            foreach ($list as $k => $v) {
                if ($v['image']) {
                    $list[$k]['image'] = $this->_formatImg($v['image']);
                }
                if (!empty($v['mobile'])) {
                    $user_info = $this->_getUserInfo($v['mobile']);
                    $list[$k]['user_avatar'] = $user_info['avatar'];
                }
                unset($list[$k]['mobile']);
            }
        } else {
            $this->returnJsonMsg('1014', [], Common::C('code', '1014'));
        }
        $this->returnJsonMsg('200', $list, Common::C('code', '200'));
    }

    /**
     * 我的服务
     * @return array
     */
    public function actionMyService()
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
        $page      = RequestHelper::get('page', '1', 'intval');
        $page_size = RequestHelper::get('page_size', '6', 'intval');
        if ($page_size > Common::C('maxPageSize')) {
            $this->returnJsonMsg('705', [], Common::C('code', '705'));
        }
        $service_model = new Service();
        $where['is_deleted']   = '2';
        $fields = 'id,mobile,category_id,son_category_id,image,title,price,unit,service_way,description,audit_status,status';
        $list = $service_model->getPageList($where, $fields, 'id desc', $page, $page_size);
        if (empty($list)) {
            $this->returnJsonMsg('1009', [], Common::C('code', '1009'));
        }
        foreach ($list as $k => $v) {
            if ($v['image']) {
                $list[$k]['image'] = $this->_formatImg($v['image']);
            }
            unset($list[$k]['mobile']);
        }
        $this->returnJsonMsg('200', $list, Common::C('code', '200'));
    }

    /**
     * 获取服务分类
     * @return array
     */
    public function actionGetCategory()
    {
        $type = RequestHelper::get('type', '0', 'intval');
        if (empty($type)) {
            $this->returnJsonMsg('1008', [], Common::C('code', '1008'));
        }
        $service_category_model = new ServiceCategory();
        $where['pid']        = '0';
        $where['status']     = '2';
        $where['is_deleted'] = '2';
        $fields = 'id,name,image';
        $order  = 'sort desc';
        $info = $service_category_model->getList($where, $fields, $order);
        if (!empty($info)) {
            foreach ($info as $k => $v) {
                if ($v['image']) {
                    $info[$k]['image'] = $this->_formatImg($v['image']);
                }
                if ($type == '2') {
                    $info[$k]['son'] = $this->_getSonCategory($v['id']);
                }
            }
        }
        $this->returnJsonMsg('200', $info, Common::C('code', '200'));
    }

    /**
     * 格式化图片
     * @param string $image 图片地址
     * @return string
     */
    private function _formatImg($image = '')
    {
        if (!empty($image)) {
            if (!strstr($image, 'http')) {
                return Common::C('imgHost').$image;
            }
        }
        return '';
    }

    /**
     * 获取子类的信息
     * @param int $pid 父类ID
     * @return array
     */
    private function _getSonCategory($pid = 0)
    {
        if (!empty($pid)) {
            $service_category_model = new ServiceCategory();
            $fields = 'id,name,image';
            $where['pid']        = $pid;
            $where['status']     = '2';
            $where['is_deleted'] = '2';
            $order  = 'sort desc';
            $info = $service_category_model->getList($where, $fields, $order);
            if (!empty($info)) {
                foreach ($info as $k => $v) {
                    if ($v['image']) {
                        $info[$k]['image'] = $this->_formatImg($v['image']);
                    }
                }
            }
            return $info;
        }
        return [];
    }

    /**
     * 获取用户信息
     * @param string $mobile 电话
     * @return array
     */
    private function _getUserInfo($mobile = '')
    {
        $user_base_info_model = new UserBasicInfo();
        $user_base_info_where['mobile'] = $mobile;
        $user_base_info_fields = 'avatar';
        $rs['avatar']   = '';
        $rs['nickname'] = '';
        $rs = $user_base_info_model->getInfo($user_base_info_where, true, $user_base_info_fields);
        if (!empty($rs)) {
            if ($rs['avatar']) {
                if (!strstr($rs['avatar'], 'http')) {
                    $rs['avatar'] = Common::C('imgHost').$rs['avatar'];
                }
            }
        }
        return $rs;
    }
}
