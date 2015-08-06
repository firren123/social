<?php
/**
 * YII AR 操作类
 *
 * PHP Version 5
 *
 * @category  Social
 * @package   BASE
 * @author    linxinliang <linxinliang@iyangpin.com>
 * @time      2015/8/05 09:21
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      linxinliang@iyangpin.com
 */

namespace frontend\models;


use yii\db\ActiveRecord;

/**
 * BASE
 *
 * @category Social
 * @package  BASE
 * @author   linxinliang <linxinliang@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     linxinliang@iyangpin.com
 */
class Base extends ActiveRecord
{
    /**
     * 获取列表
     * @param array  $cond      条件
     * @param string $field     字段
     * @param string $order     排序
     * @param string $and_where like != 的情况
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getList($cond = array(), $field = '*', $order = '', $and_where = '')
    {
        $list = [];
        if ($cond || $and_where) {
            $list = $this->find()
                ->select($field)
                ->where($cond)
                ->andWhere($and_where)
                ->orderBy($order)
                ->asArray()
                ->all();
        }
        return $list;
    }

    /**
     * 获取分页列表
     * @param array  $cond      条件
     * @param string $field     字段
     * @param string $order     排序
     * @param int    $page      当前页数
     * @param int    $size      每页显示几条
     * @param string $and_where like != 的情况
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getPageList($cond = array(), $field = '*', $order = '', $page = 1, $size = 10, $and_where = '')
    {
        $list = [];
        if ($cond || $and_where) {
            $list = $this->find()
                ->select($field)
                ->where($cond)
                ->andWhere($and_where)
                ->orderBy($order)
                ->offset(($page-1) * $size)
                ->limit($size)
                ->asArray()
                ->all();
        }
        return $list;
    }

    /**
     * 获取记录数
     * @param array  $cond      条件
     * @param string $and_where like != 的情况
     * @return int|string
     */
    public function getCount($cond = array(), $and_where = '')
    {
        $num = 0;
        if ($cond || $and_where) {
            $num = $this->find()->where($cond)->andWhere($and_where)->count();
        }
        return $num;
    }

    /**
     * 获取信息 一条
     * @param array  $cond      条件
     * @param bool   $asArray   是否作为数组返回
     * @param string $field     字段
     * @param string $and_where 字段
     * @param string $order     排序
     * @return array|null|ActiveRecord
     */
    public function getInfo($cond = array(), $asArray = true, $field = '*', $and_where = '', $order = '')
    {
        $info = [];
        if ($cond) {
            if ($asArray) {
                $info = $this->find()->select($field)->where($cond)->andWhere($and_where)->orderBy($order)->asArray()->one();
            } else {
                $info = $this->find()->select($field)->where($cond)->andWhere($and_where)->orderBy($order)->one();
            }

        }
        return $info;

    }

    /**
     * 更新信息
     * @param array $data 数据
     * @param array $cond 条件
     * @return bool
     */
    public function updateInfo($data = array(), $cond = array())
    {
        $re = false;
        if ($cond && $data) {
            $re = $this->updateAll($data, $cond);
        }
        return $re !== false;
    }

    /**
     * 插入信息
     * @param array $data 数据
     * @return bool
     */
    public function insertInfo($data = array())
    {
        $re = false;
        if ($data) {
            $model = clone $this;
            foreach ($data as $k=>$v) {
                $model->$k = $v;
            }
            $re = $model->save();
        }
        return $re !== false;
    }
}
