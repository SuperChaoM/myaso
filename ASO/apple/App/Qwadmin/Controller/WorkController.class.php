<?php
/**
*
* 版权所有：河马社区<www.hemashequ.com>
* 作    者：小河马
* 日    期：2015-09-18
* 版    本：1.0.0
* 功能说明：个人中心控制器。
**/

namespace Qwadmin\Controller;

class WorkController extends ComController {

    public function work_group() {
        $config = ['app_id'=>''];
        $param = $this->get_param($config);
        $where = ['is_del' => 1];
        if ($param['app_id']) {
            $where['app_id'] = $param['app_id'];
        }
        $list = M('work')
            ->field('app_id,work_name,sum(down_num) as down_num, sum(accept_num) as accept_num, sum(succ_num) as succ_num,
            max(updated_at) as updated_at, sum(impression_num) as impression_num, sum(openapp_num) as openapp_num,
            sum(fail_num) as fail_num')
            ->where($where)->order('work_id desc')
            ->group('app_id')
            ->auto_page()->select();
        $this->get_left_app_num($list);
        $this->assign('list',$list);
        $this->assign('status_name', \WorkStatus::$status_name);
        $this->assign($param);
        $this->assign($_REQUEST);
        $this->display();
    }

	public function index(){
        $config = ['work' =>'', 'app_id'=>''];
        $param = $this->get_param($config);
        $where = ['is_del' => 1];
        if ($param['work']) {
            $where['_complex'] = [
                '_logic' => 'or',
                'work_num' => $param['work'],
                'keyword'=> ['like', "%{$param['work']}%"],
            ];
        }
        if ($param['app_id']) {
            $where['app_id'] = $param['app_id'];
        }
		$list = M('work')->where($where)->order('status asc, work_id desc')->auto_page()->select();
        
        $keywords = array();
        foreach ($list as $d) {
            $keywords[] = $d['keyword'];
        }
        $this->assign('keywords',join($keywords, ','));

        $this->get_left_app_num($list);
		$this->assign('list',$list);
        $this->assign('status_name', \WorkStatus::$status_name);
        $this->assign($param);
        $this->assign($_REQUEST);
		$this->display();
	}

    private function get_left_app_num(&$list) {
        if (empty($list)) {
            return;
        }
        $app_id_map = [];
        foreach ($list as &$data) {
            if ($app_id_map[$data['app_id']]) {
                $cnt = $app_id_map[$data['app_id']];
            } else {
                $pos = M('app_pos')->where(['app_id' => $data['app_id']])->getField('pos');
                $where = ['id' => ['gt', $pos], 'status' => \AccountStatus::NORMAL];
                $cnt = M('download')->where($where)->count();
                $app_id_map[$data['app_id']] = $cnt;
            }
            $data['left_num'] = $cnt;
        }
    }
    private function get_work_num() {
        $num = M('work')->where(['created_at' => ['egt', date('Y-m-d')]])->count()+1;
        $num = str_pad($num, 3, 0, STR_PAD_LEFT);
        return  substr(date('Ymd'),2,10).$num;
    }
	
	public function add(){
        if (IS_POST) {
            $config = [
                'work_name' =>self::NOT_EMPTY,
                'app_id' =>self::NUMBER,
                'tpl_id' => self::NUMBER,
                'app_name' => self::NOT_EMPTY,
                'down_num' => self::NUMBER,
                'keyword' => self::NOT_EMPTY,
            ];
            $data = $this->get_param($config);
            $keyword = str_replace('，',',', $data['keyword']);
            $data ['work_num'] = $this->get_work_num();
            if ($data['tpl_id']) {
                $data['work_plain'] = M('work_tpl')->where(['tpl_id'=> $data['tpl_id']])->getField('plain');
                $plain = unserialize($data['work_plain']);
                $time  = date('H:i', intval(time()/1800)* 1800);
                if ($plain[$time]) {
                    $data['down_num'] = intval($plain[$time]);
                }
            }
            $keywordList = explode(',', $keyword);
            M()->startTrans();
            foreach ($keywordList as $key) {
                $data['keyword'] = $key;
                M('work')->add($data);
            }
            M()->commit();

            $this->success('新增成功', U('index'));
        }

        $tpl_list = M('work_tpl')->field('tpl_id,tpl_name')->select();
        $this->assign('tpl_list', $tpl_list);
        $this->display();
	}

    public function del() {
        $work_id = intval($_REQUEST['work_id']);
        $m_work = M('work');
        $m_work->where(['work_id' => $work_id])->setField('is_del', 2);
        $this->success('删除成功');
    }

    public function set_status() {
        $config = ['status' => self::NUMBER, 'work_id' => self::NUMBER];
        $data = $this->get_param($config);
        $update_data = ['status' => $data['status']];
        M('work')->where(['work_id' => $data['work_id']])->save($update_data);
        if ($data['status'] == \WorkStatus::RUNNING) {
            $this->success('任务开启成功');
        }
        if ($data['status'] == \WorkStatus::HANDLE) {
            $this->success('任务停止成功');
        }
    }

    public function edit() {
        $work_id = $_REQUEST['work_id'];
        $m_work = M('work');
        if (IS_POST) {
            $config = [
                'work_name' =>self::NOT_EMPTY,
                'app_id' =>self::NUMBER,
                'app_name' => self::NOT_EMPTY,
                'down_num' => self::NUMBER,
                'tpl_id' => self::NUMBER,
                'keyword' => self::NOT_EMPTY,
            ];
            $data = $this->get_param($config);
            if ($data['tpl_id']) {
                $data['work_plain'] = M('work_tpl')->where(['tpl_id'=> $data['tpl_id']])->getField('plain');
            }
            $data['keyword'] = str_replace('，',',', $data['keyword']);
            $m_work->where(['work_id' => $work_id])->save($data);
            $this->success('修改成功!', U('index',['app_id' => $data['app_id']]));
        }
        $vo = $m_work->where(['work_id' => $work_id])->find();

        $tpl_list = M('work_tpl')->field('tpl_id,tpl_name')->select();
        $this->assign('tpl_list', $tpl_list);
        $this->assign('vo', $vo);
        $this->display();
    }

    public function task_list() {
        import_org('IP/IP.class.php');
        $config = ['work_id' => self::NUMBER, 'device_num' =>'', 'status' =>''];
        $param = $this->get_param($config);

        $where = ['work_id' => $param['work_id']];
        if ($param['status']) {
            $where['status'] = $param['status'];
        }
        if ($param['device_num']) {
            $where['device'] = $param['device_num'];
           // $where['device_id'] = get_device_id($param['device_num']);
        }

        $work_info = M('work')->where(['work_id' => $param['work_id']])->find();
        $task_list = M('work_item')->where($where)->auto_page()->order('item_id desc')->select();
        // foreach ($task_list as $k => $item) {
        //     if ($item['ip']) {
        //         $address = \IP::find($item['ip']);
        //         $task_list[$k]['address'] = trim(join(',', $address),',');
        //     }
        // }
        // format_device_num($task_list);

        $this->assign('work_info', $work_info);
        $this->assign('task_list', $task_list);
        $this->assign('status_name', \TaskStatus::$status_name);
        $this->assign($param);
        $this->display();
    }

    /**
     * @功能：工作计划
     */
    public function plain() {
        $config = ['work_id' => self::NUMBER];
        $param = $this->get_param($config);

        $work_info = M('work')->where(['work_id' => $param['work_id']])->find();
        $work_plain= empty($work_info['work_plain']) ? $this->get_init_plain() : unserialize($work_info['work_plain']);

        $this->assign('work_info', $work_info);
        $this->assign('work_plain', json_encode($work_plain, JSON_UNESCAPED_UNICODE));
        $this->assign('plain_x', json_encode(array_keys($work_plain)));
        $this->assign('plain_y', json_encode(array_values($work_plain)));
        $this->display();
    }

    public function save_plain() {
        $config = ['work_id' => self::NUMBER,'plain'=>''];
        $param = $this->get_param($config);
        $where = ['work_id' => $param['work_id']];
        $data  = ['work_plain' => serialize($param['plain'])];
        $app_id = M('work')->where($where)->getField('app_id');
        M('work')->where($where)->save($data);
        $this->success('保存成功', U('index',['app_id' => $app_id]));
    }

    //获取初始化工作计划
    private function get_init_plain() {
        $result = [];
        for($i=0; $i<24;++$i) {
            $plain_0 = str_pad($i,2,'0', STR_PAD_LEFT).':00';
            $plain_1 = str_pad($i,2,'0', STR_PAD_LEFT).':30';
            $result[$plain_0] = 0;
            $result[$plain_1] = 0;
        }
        return $result;
    }

    /**
     * @功能：计划汇总
     */
    public function plain_count() {
        $m_work = M('work');
        $where = [
            'is_del'  => 1,
            'status'  => ['in', [\WorkStatus::RUNNING, \WorkStatus::SUCC]],
            '_string' => 'work_plain is not null'
        ];
        $work_list = $m_work->where($where)->select();
        $plain_count = $this->get_init_plain();
        foreach ($work_list as $work) {
            $plain = unserialize($work['work_plain']);
            if (empty($plain)) {
                continue;
            }
            foreach ($plain as $time => $num) {
                $plain_count[$time] += $num;
            }
        }
        $this->assign('work_plain', json_encode($plain_count));
        $this->assign('plain_x', json_encode(array_keys($plain_count)));
        $this->assign('plain_y', json_encode(array_values($plain_count)));
        $this->assign('sum_num', array_sum($plain_count));
        $this->display();
    }

    public function warn_report() {
        $config = ['create_time_s' => '', 'create_time_e' => '','device' =>'', 'type' => ''];
        $param  = $this->get_param($config);

        $where = [];
        $time_where_list = [];
        if ($param['device']) {
            if ($param['type'] ==1) {
                $sql = M('device')->where(['device_num' => ['like', "{$param['device']}%"]])->field('device_id')->select(false);
                $time_where_list[] = " device_id in ($sql)";
            } else {
                $where['device'] = ['like', "{$param['device']}%"];
            }
        }
        if ($param['type']) {
            $where['type'] = $param['type'];
        }
        if ($param['create_time_s']) {
            $time = date('Y-m-d', strtotime($param['create_time_s']));
            $time_where_list[] = "created_at >='{$time}'";
        }
        if ($param['create_time_e']) {
            $time = date('Y-m-d 23:59:59', strtotime($param['create_time_e']));
            $time_where_list[] = "created_at <='{$time}'";
        }
        if (!empty($time_where_list)) {
            $where['_string'] = join( ' and ', $time_where_list);
        }

        $list = M('warn')
            ->field('*,device as device_main')
            ->where($where)->auto_page()
            ->order('created_at desc, succ_num asc, fail_num desc')->select();
        format_device_num($list);
        foreach ($list as $k => $v) {
            if ($v['device_num']) {
                $list[$k]['device'] = $v['device_num'];
            }
        }

        $type_list = [
            1 => '过检',
            2 => 'ASO',
        ];
        $this->assign('type_list', $type_list);
        $this->assign('list', $list);
        $this->assign($param);
        $this->display();
    }

    /**
     * @功能：用APPID删除
     */
    public function app_del() {
        if (IS_POST) {
            $config = ['app_id' => self::NUMBER];
            $param  = $this->get_param($config);
            $data = [
                'is_del' => 2,
            ];
            M('work')->where($param)->save($data);
            $this->success('删除成功!', U('index'));
        }
        $this->display();
    }
}