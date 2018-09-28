<?php
/**
*
* 版权所有：恰维网络<qwadmin.qiawei.com>
* 作    者：寒川<hanchuan@qiawei.com>
* 日    期：2016-01-21
* 版    本：1.0.0
* 功能说明：前台控制器演示。
*
**/
namespace Wap\Controller;

use Think\Controller;
use Think\Log;

class IndexController extends WapController{

    public function index() {
        $this->display();
    }

    /**
     * @功能：投票
     */
    public function vote_submit() {
        $config = [
            'user_name' => self::NOT_EMPTY,
            'user_phone'=> self::NOT_EMPTY,
            'vote_url'  => self::NOT_EMPTY,
            'vote_num'  => self::NOT_EMPTY,
        ];

        $param = $this->get_param($config);
        $data = [
            '投票对象' => $param['user_name'],
            '投票数量' => $param['vote_num'],
            '投票地址' => "<a href='{$param['vote_url']}'>{$param['vote_url']}</a>",
            '联系电话' => "<a href='tel:{$param['user_phone']}'>{$param['user_phone']}</a>",
            '下单时间' => date('Y-m-d H:i:s'),
        ];
        echo json_encode(array('ret' => 0, 'data' => '下单成功'), JSON_UNESCAPED_UNICODE);

        $this->send_mail('新的下单', $data);
    }
}