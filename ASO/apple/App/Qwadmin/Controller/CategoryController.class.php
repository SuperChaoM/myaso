<?php
/**
*
* 版权所有：恰维网络<qwadmin.qiawei.com>
* 作    者：寒川<hanchuan@qiawei.com>
* 日    期：2015-09-18
* 版    本：1.0.0
* 功能说明：用户管理。
*
**/

namespace Qwadmin\Controller;

class CategoryController extends ComController {
    /**
     * @功能：帮助列表
     * @开发者：小菜鸟
     */
    public function index() {
        $list = M('category')->order('sort asc')->select();
        $this->assign('list', $list);
        $this->display();
    }

    /**
     * @功能：新增帮助
     * @开发者：小菜鸟
     */
    public function add() {
        if (IS_POST) {
            $config = [
                'category_name' => self::NOT_EMPTY,
                'summary' => self::NOT_EMPTY,
                'img_url' => self::NOT_EMPTY,
                'help' => self::NOT_EMPTY,
                'sort' => self::NUMBER,
            ];
            $param = $this->get_param($config);

            $m_help = M('category');
            $m_help->add($param);

            $this->success('新增成功!', U('index'));
        }
        $this->display();
    }

    /**
     * @功能：新增帮助
     * @开发者：小菜鸟
     */
    public function edit() {
        if (IS_POST) {
            $config = [
                'category_id' => self::NUMBER,
                'category_name' => self::NOT_EMPTY,
                'summary' => self::NOT_EMPTY,
                'img_url' => self::NOT_EMPTY,
                'help' => self::NOT_EMPTY,
                'sort' => self::NUMBER,
            ];
            $param = $this->get_param($config);

            $m_help = M('category');
            $m_help->where(['category_id' => $param['category_id']])->save($param);

            $this->success('修改成功!', U('index'));
            exit;
        }

        $config = ['category_id' => self::NUMBER];
        $param  = $this->get_param($config);
        $help = M('category')->where($param)->find();
        $this->assign('vo', $help);
        $this->display();
    }

    public function del() {
        $config = ['category_id' => self::NUMBER];
        $param  = $this->get_param($config);
        M('category')->where($param)->delete();
        $this->success('删除成功', U('index'));
    }
}