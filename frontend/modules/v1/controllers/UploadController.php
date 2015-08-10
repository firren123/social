<?php
/**
 * 上传类
 *
 * PHP Version 5
 *
 * @category  Social
 * @package   UPLOAD
 * @author    linxinliang <linxinliang@iyangpin.com>
 * @time      2015-5-27 09:31
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      linxinliang@iyangpin.com
 */

namespace frontend\modules\v1\controllers;

use Yii;
use common\helpers\FastDFSHelper;
use yii\web\Controller;

/**
 * 上传类
 *
 * @category Social
 * @package  UPLOAD
 * @author   linxinliang <linxinliang@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     linxinliang@iyangpin.com
 */
class UploadController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * 上传图片
     * @return array
     */
    public function actionUploadImg()
    {
        $rs = ['state'=>'ERROR'];
        $fastDfs = new FastDFSHelper();
        $rs_data = $fastDfs->fdfs_upload('file');
        if ($rs_data) {
            $rs['state'] = 'SUCCESS';
            $rs['url'] = $rs_data['group_name'].'/'.$rs_data['filename'];
        }
        echo json_encode($rs);
    }
}
