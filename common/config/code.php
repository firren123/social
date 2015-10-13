<?php
/**
 * 标识码
 *
 * PHP Version 5
 *
 * @category  Social
 * @package   BASE
 * @author    linxinliang <linxinliang@iyangpin.com>
 * @time      2015/8/05
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      linxinliang@iyangpin.com
 */
return [
    '200'  => 'SUCCESS',
    '400'  => '服务器繁忙',
    /**500 - 600 段 表示验签相关错误**/
    /**600 - 700 段 表示用户级别错误**/
    /**700 - 800 段 表示帖子级别错误**/
    /**800 - 900 段 表示便利店级别错误**/
    /**900 - 1000 段 表示相关充值业务订单表级别错误**/
    /**1000 - 1100 段 表示发布服务级别错误**/
    /**1100 - 1200 段 表示提现相关错误**/

    '501' => '缺少参数 appId',
    '502' => '缺少参数 timestamp',
    '503' => '缺少参数 sign',
    '504' => '缺少参数 mobile',
    '505' => '签名验证失败',
    '506' => 'appID 错误',
    '507' => 'Token 不能为空',
    '508' => 'Token 过期',
    '509' => '缺少参数 dev',
    '510' => 'dev 参数不正确',

    '601' => '用户禁用，请联系管理员',
    '602' => '账号不存在',
    '603' => '账户异常请重新登陆',
    '604' => '用户名不能为空',
    '605' => '手机号格式不正确',
    '606' => '密码不能为空',
    '607' => '密码错误',
    '608' => '验证码不能为空',
    '609' => '验证码失效',
    '610' => '验证码错误',
    '611' => '发送验证码失败',
    '612' => '修改密码失败',
    '613' => '缺少参数 channel_user_id',
    '614' => '缺少参数 channel',
    '615' => '非法的 channel 值',
    '616' => '未曾绑定过该第三方账号',
    '617' => '还没绑定本系统账号',
    '618' => '已经绑定过本系统账号',
    '619' => '绑定本系统账号失败',
    '620' => '该用户已经存在',
    '621' => '缺少参数 uid',
    '622' => '缺少更新参数',
    '623' => '编辑个人资料失败',
    '624' => '上传图片失败',
    '625' => '缺少图片信息',
    '626' => '环信注册失败',
    '627' => '用户还未登陆过',
    '628' => '收货人不能为空',
    '629' => '收货人手机号不能为空',
    '630' => '收货人性别不能为空',
    '631' => '省 ID不能为空',
    '632' => '市 ID不能为空',
    '633' => '区 ID不能为空',
    '634' => '缺少参数 address_id',
    '635' => '检索地址不能为空',
    '636' => '楼号、单元和门牌号不能为空',
    '637' => '检索关键字不能为空',
    '638' => '缺少参数 province_id',
    '639' => '环信注册成功，但是添加客服好友失败',
    '640' => '缺少省份名称',
    '641' => '缺少参数 uuid',
    '642' => '缺少参数 community_id',
    '643' => '缺少参数 push_channel',
    '644' => '缺少参数 push_id',
    '645' => '缺少参数 community_city_id',
    '646' => '最多只能创建10个收货地址',

    '701' => '版块ID不能为空',
    '702' => '帖子标题不能为空',
    '703' => '帖子内容不能为空',
    '704' => '该帖子已经存在',
    '705' => '已超过最大数量',
    '706' => '帖子ID不能为空',
    '707' => '帖子不存在',
    '708' => '暂无帖子',
    '709' => '暂无评论',
    '710' => '暂无版块',
    '711' => '版块的父类ID不能为空',
    '712' => '子类暂无版块',
    '713' => '评论ID不能为空',
    '714' => '评论不存在',
    '715' => '评论内容不能为空',
    '716' => '已对该评论点过赞',
    '717' => '未曾对该评论点过赞',
    '718' => '已对该帖子点过赞',
    '719' => '未曾对该帖子点过赞',

    '801' => '缺少参数 lng(经度)',
    '802' => '缺少参数 lat(纬度)',
    '803' => '缺少参数 shop_id',
    '804' => '订单状态传递错误',
    '805' => '缺少参数 order_sn',
    '806' => '请检查订单状态，发货状态才能确认收货',
    '807' => '已退款的订单不能可以取消订单',
    '808' => '缺少参数 product_id',
    '809' => '缺少参数 product_name',
    '810' => '缺少参数 type(售后类型)',
    '811' => '缺少参数 price(商品价格)',
    '812' => '缺少参数 pay_type_id(支付方式ID)',
    '813' => '缺少参数 type(红包类型1=支付2=评价)',
    '814' => '不支持发红包（后台没有配置可用的发放规则）',
    '815' => '只能分享一次红包给朋友们',
    '816' => '未能查询到订单信息',
    '817' => '当前商品已经申请了退换货操作',
    '818' => '未能查询出当前订单信息',
    '819' => '退换货数量不能超过订单中的商品数量',

    '900' => '缺少参数 business_code',
    '901' => 'business_type 传递错误',
    '902' => 'source_type 传递错误',

    '1000' => '缺少参数 category_id',
    '1001' => '缺少参数 son_category_id',
    '1002' => '缺少参数 image',
    '1003' => '缺少参数 title',
    '1004' => '缺少参数 price',
    '1005' => '缺少参数 unit',
    '1006' => '缺少参数 service_way',
    '1007' => '缺少参数 description',
    '1008' => '缺少参数 type',
    '1009' => '暂无首页服务',
    '1010' => '缺少参数 service_id',
    '1011' => '未能查询到当前 service_id 的信息',
    '1012' => '缺少参数 status',
    '1013' => '已经是当前状态，无需操作',
    '1014' => '非法的参数 type',
    '1015' => '未能查询到服务(店铺)设置信息',
    '1016' => '非法请求 设置服务信息',
    '1017' => '请输入18位的身份证号',
    '1018' => '请检查身份证号是否正确',
    '1019' => '请先开通店铺(服务)信息',
    '1020' => '该服务正在审核中，不可进行编辑',
    '1021' => '只有认证失败，才可以重新进行认证',
    '1023' => '缺少参数 day',
    '1024' => '还没有设置服务时间，请进行设置',
    '1025' => '缺少参数 hour',
    '1026' => '缺少参数 status',
    '1027' => '该时间段已经启用中',
    '1028' => '该时间段已经禁用中',
    '1029' => '该时间段未在服务时间内，请进行设置',
    '1030' => '无效的JSON数据',
    '1031' => '缺少参数 appointment_service_time',
    '1032' => '缺少参数 appointment_service_address',
    '1033' => '缺少参数 source_type',
    '1034' => '暂无订单',
    '1035' => '缺少参数 uuid',
    '1036' => '当前时间段不可预约(超过最大预约数或商家设为不可预约)',
    '1037' => '缺少参数 type',
    '1038' => '用户信息还未被认证成功',
    '1039' => '后台暂未设置服务单位',
    '1040' => '服务时间中包含重复时间段',
    '1041' => '该时间段已被用户预约，不可取消',
    '1042' => '缺少参数 order_sn',
    '1043' => '未能查询到当前 order_sn 的信息',
    '1044' => '服务人(店铺)信息审核失败',
    '1045' => '自己不能预约自己发布的服务',
    '1046' => '缺少参数 order_status',
    '1047' => '已经对该订单进行评价过',
    '1048' => '只有已经确认且已支付的订单才可以进行开始服务',
    '1049' => '只有未确认且支付的订单才可以进行确认服务',
    '1050' => '只有未确认且未支付的订单才可以进行取消服务',
    '1051' => '只有进行中或等待确认且已支付的订单才可以进行完成服务',
    '1052' => '实名认证暂未审核成功',

    '1100' => '缺少参数 real_name',
    '1101' => '缺少参数 bank_card',
    '1102' => '已添加过这张银行卡',
    '1103' => '您还未绑定过银行卡',
    '1104' => '银行卡尚未绑定过',
    '1105' => '缺少参数 money',
    '1106' => '缺少参数 withdrawal_id',
    '1107' => '暂无当前 withdrawal_id 的提现信息',
    '1108' => '暂无提现信息列表',


    //SSDB KEY 备注
    'open_province'           => 'CRM中已经开通的省份',
    'province_name_{id}'      => '开通省份的名称',
    'address_details_{id}'    => '用户收货地址详情',
    'address_list_{mobile}'   => '用户收货地址列表',
    'profile_{mobile}'        => '用户个人信息',
    'service_top_category'    => '顶级服务分类',
    'service_son_category_{pid}' => '子服务分类',
    'service_unit' => '服务单位',
];