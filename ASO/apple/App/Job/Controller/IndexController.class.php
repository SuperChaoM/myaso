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
namespace Job\Controller;

use Think\Controller;
use Think\Log;

class IndexController {
    public function task_plain() {
        $now_time = date('H:i');
        Log::info('work_start:'.$now_time, 'task_plain.log');

        $m_work = M('work');
        $where = [
            'is_del'  => 1,
            'status'  => ['in', [\WorkStatus::RUNNING, \WorkStatus::SUCC]],
            '_string' => 'work_plain is not null'
        ];
        $work_list = $m_work->where($where)->select();
        foreach ($work_list as $work) {
            $plain = unserialize($work['work_plain']);
            if ($plain[$now_time]) {
                Log::info("{$work['work_id']}-{$work['work_name']}-{$work['keyword']}执行任务", 'task_plain.log');
                Log::info("旧目标量: ". $work['down_num'], 'task_plain.log');
                $down_num = $work['down_num'] + intval($plain[$now_time]);
                Log::info("新目标量: ". $down_num, 'task_plain.log');
                $update_where = [ 'work_id' => $work['work_id']];
                $update_data = [
                    'down_num' => $down_num,
                    'status' => \WorkStatus::RUNNING,
                ];
                $ret = $m_work->where($update_where)->setField($update_data);
                Log::info($update_data, 'task_plain.log');
                Log::info($m_work->getLastSql(), 'task_plain.log');
                if ($ret !== false){
                    Log::info('update succ, affected rows: '. $ret, 'task_plain.log');
                }
            } else {
                Log::info("{$work['work_id']}-{$work['work_name']}-{$work['keyword']}暂时无任务", 'task_plain.log');
            }
        }
        Log::info('work_end', 'task_plain.log');
        echo "执行完毕\n";
    }

    /**
     * @功能：预警
     */
    public function warn_report() {
        $now_date = date('Y-m-d H:i:s');
        $where = ['created_at' => ['gt', date('Y-m-d H:i:s', time()-3600*6)]];
        $m_warn = M('warn');
        $has_warn_list = $m_warn->where($where)->select();
        $has_warn = list2map($has_warn_list,'device_id');

        //过检,最近6小时失败的
        $m_check = M('check');
        $check_list = $m_check->field(['device_id,sum(if(status=\'成功\',1,0)) as succ_num  ,sum(if(status=\'失败\',1,0)) as fail_num'])
            ->where($where)->group('device_id')->select();

        $m_warn->startTrans();
        foreach($check_list as $check) {
            if (!$has_warn[$check['device_id']]) {
                $data = [
                    'type' => 1,
                    'device_id' => $check['device_id'],
                    'device' => $check['device'].'',
                    'created_at' => $now_date,
                    'note' => "过检 失败:{$check['fail_num']} 成功:{$check['succ_num']}",
                    'succ_num' => $check['succ_num'],
                    'fail_num' => $check['fail_num'],
                ];
                $m_warn->add($data);
            }
        }
        $m_warn->commit();

        $m_warn->startTrans();
        $aso_list = M('task')->field(['device_id,device, sum(if(status=3,1,0)) as succ_num  ,sum(if(status=3,0,1)) as fail_num'])
            ->where($where)->group('device_id')->select();
        foreach ($aso_list as $aso) {
            if (!$has_warn[$aso['device_id']]) {
                $data = [
                    'type' => 2,
                    'device_id' => $aso['device_id'],
                    'device' => $aso['device'].'',
                    'created_at' => $now_date,
                    'note' => "ASO 失败:{$aso['fail_num']} 成功:{$aso['succ_num']}",
                    'succ_num' => $aso['succ_num'],
                    'fail_num' => $aso['fail_num'],
                ];
                $m_warn->add($data);
            }
        }
        $m_warn->commit();

        echo "done";
    }

    public function get_mixed_task() {
        $step = get_time_step();
        if ($step === false) {
            echo "step : false\n";
            exit;
        }
        $rate = 0.1;
        $redis = get_redis();

        $len = $redis->lLen(WORK_TASK_LIST);
        if ($len > 10000) {
            Log::error("max len:{$len}",'get_mixed_task.log');
            exit;
        } else {
            Log::info("now len:{$len}",'get_mixed_task.log');
        }

        sleep(5);
        Log::info('work_start', 'get_mixed_task.log');
        ini_set('memory_limit', '1024M');

        $m_work = M('work');
        $where = [
            'is_del'  => 1,
            'status'  => \WorkStatus::RUNNING,
            '_string' => 'down_num > succ_num'
        ];
        $work_list = $m_work->where($where)->select();
        $data_list = [];
        foreach ($work_list as $work) {
            $diff_num = ($work['down_num'] - $work['succ_num']) * $rate;
            $diff_num = intval($diff_num);
            if ($diff_num < 1) {
                Log::warn("{$work['work_name']}-{$work['keyword']} 没有TASK", 'get_mixed_task.log');
                continue;
            }

            // 初始化缓存key
            $cache_key = WORK_TASK_STATS . '-' . $work['work_id'];
            if (!$redis->exists($cache_key)){
                $redis->hIncrBy($cache_key, 'impression_num', $work['impression_num']);
                $redis->hIncrBy($cache_key, 'openapp_num', $work['openapp_num']);
                $redis->hIncrBy($cache_key, 'succ_num', $work['succ_num']);
                $redis->hIncrBy($cache_key, 'queued_tasks', $work['succ_num']);
                $redis->hIncrBy($cache_key, 'fail_num', $work['fail_num']);
                Log::info('init stats nums for work '. $work['work_id'].' '.$work['impression_num'].'/'.$work['openapp_num'].'/'.$work['succ_num'].'/'.$work['fail_num']);
            }

            $queuedTasksNum = $redis->hGet($cache_key, 'queued_tasks');
            Log::info("{$work['work_name']}-{$work['keyword']} queued_tasks " . $queuedTasksNum, 'get_mixed_task.log');
            if ($queuedTasksNum > 2.25 * $work['down_num']){
                Log::warn("{$work['work_name']}-{$work['keyword']} 已经给了太多了 超过225%", 'get_mixed_task.log');
                continue;
            }

            $pos = M('app_pos')->where(['app_id' => $work['app_id']])->getField('pos');
            $pos = intval($pos);

            $where = [
                'id' => ['gt', $pos],
                'status' => \AccountStatus::NORMAL,
            ];
            $account_list = M('download')->where($where)->limit($diff_num)
                ->order('id asc')->select();
            $end_account = end($account_list);
            $max_pos = $end_account['id'];
            if (empty($max_pos)) {
                Log::warn("{$work['work_name']}-{$work['keyword']} 没有ID", 'get_mixed_task.log');
                continue;
            } else {
                $work['diff_num'] = $diff_num;
                $work['get_num'] = count($account_list);
                Log::info($work, 'get_mixed_task.log');
                Log::info("{$work['work_name']}-{$work['keyword']} ID_NUM:".count($account_list), 'get_mixed_task.log');
            }

            $pos_data = ['pos' => $max_pos, 'app_id' => $work['app_id']];
            M('app_pos')->add($pos_data,[],true);

            // 将进入队列的数量保存到缓存中
            $redis->hIncrBy($cache_key, 'queued_tasks', count($account_list));

            foreach ($account_list as $item) {
                $data_list[] = [
                    'work_id' => $work['work_id'],
                    'app_id' => $work['app_id'],
                    'keyword'=> $work['keyword'],
                    'app_name' => $work['app_name'],
                    'account_id'=> $item['id'],
                    'email'     => $item['email'],
                    'password'  => $item['password'],
                    'idfa'      => $item['idfa'],
                    'serial'    => $item['serial'],
                    'imei'    => $item['imei'],
                    'bt'    => $item['bt'],
                    'wifi'    => $item['wifi'],
                    'udid'      => $item['udid'],
                    'ecid'      => $item['ecid'],
                    'product_type' => $item['product_type'],
                    'product_version' => $item['product_version'],
                    'build_version' => $item['build_version'],
                    'hardware_platform' => $item['hardware_platform'],
                    'hardware_model' => $item['hardware_model'],
                    'mlb_serial' => $item['mlb_serial'],
                    'idfa' => $item['idfa']
                ];
            }
        }

        if ($data_list) {
            shuffle($data_list);
            $redis->multi();
            foreach ($data_list as $data) {
                $redis->lPush(WORK_TASK_LIST, $data);
            }
            $redis->exec();
            Log::info('push_len:'.count($data_list), 'get_mixed_task.log');
        }

        Log::info('work_end', 'get_mixed_task.log');
        echo "执行完毕\n";
    }

    public function sync_task_stats_num() {
        Log::info('======> sync start <======','sync_task_stats_num.log');
        $minute = date('i');
        if ($minute == '30' || $minute == '00'){
            Log::info('start wait 30 seconds','sync_task_stats_num.log');
            sleep(30);
            Log::info('wait end','sync_task_stats_num.log');
        }

        $redis = get_redis();
        $m_work = M('work');
        $where = ['is_del'  => 1];
        $work_list = $m_work->where($where)->select();
        $m_work->startTrans();
        foreach ($work_list as $work) {
            Log::info('*** work '. $work['work_id']. ' 关键词 '. $work['keyword'], 'sync_task_stats_num.log');
            $cache_key = WORK_TASK_STATS . '-' . $work['work_id'];
            $cache_nums = $redis->hGetAll($cache_key);

            if (empty($cache_nums)){
                Log::info(" cache nums are not found!", 'sync_task_stats_num.log');
                continue;
            }

            $sync_where = [ 'work_id' => $work['work_id'] ];
            $data  = $m_work->where($sync_where)->field('work_id, status, accept_num, succ_num, fail_num, down_num, impression_num, openapp_num')->find();

            $data_has_update = ($cache_nums['succ_num'] > $data['succ_num']) || ($cache_nums['fail_num'] > $data['fail_num']);

            // write logs
            Log::info('  impression_num: '.$data['impression_num'].' -> '.$cache_nums['impression_num'] , 'sync_task_stats_num.log');
            Log::info('  openapp_num: '.$data['openapp_num'].' -> '.$cache_nums['openapp_num'] , 'sync_task_stats_num.log');
            Log::info('  succ_num: '.$data['succ_num'].' -> '.$cache_nums['succ_num'] , 'sync_task_stats_num.log');
            Log::info('  fail_num: '.$data['fail_num'].' -> '.$cache_nums['fail_num'] , 'sync_task_stats_num.log');
            Log::info('  accept_num: '.$data['accept_num'].' -> '.($cache_nums['succ_num']+ $cache_nums['fail_num']), 'sync_task_stats_num.log');

            if ($data_has_update){
                // update to db
                $data['impression_num'] = $cache_nums['impression_num'];
                $data['openapp_num'] = $cache_nums['openapp_num'];
                $data['succ_num'] = $cache_nums['succ_num'];
                $data['fail_num'] = $cache_nums['fail_num'];
                $data['accept_num'] =  $cache_nums['succ_num']+ $cache_nums['fail_num'];
                $data['updated_at'] = date('Y-m-d H:i:s');
                if ($data['succ_num'] >= $data['down_num']) {
                    $status = \WorkStatus::SUCC;
                    $data['status'] = $status;
                    Log::info('  update status to SUCC!!', 'sync_task_stats_num.log');
                }
                $m_work->where($sync_where)->save($data);

                Log::info('  nums is SAVED TO DB.', 'sync_task_stats_num.log');
            }else{
                Log::info('  nums are not changed.', 'sync_task_stats_num.log');
            }

        }
        $m_work->commit();
        Log::info('======> sync done <======','sync_task_stats_num.log');
    }

    public function send_events_to_graphite(){
        $fp = fsockopen('127.0.0.1', 2003, $err, $errc, 1);
        Log::info('fp'. $fp, 'send_events_to_graphite.log');
        if ($fp == FALSE){
            Log::info('fsockopen error '.$err.' '.$errc, 'send_events_to_graphite.log');
        }else{
            $redis = get_redis();
            $events = GRAPHITE_KEY_IMPRESSION_NUM.' '.$redis->get(GRAPHITE_KEY_IMPRESSION_NUM).' '.time()."\n"
                     .GRAPHITE_KEY_OPENAPP_NUM.' '.$redis->get(GRAPHITE_KEY_OPENAPP_NUM).' '.time()."\n"
                     .GRAPHITE_KEY_SUCC_NUM.' '.$redis->get(GRAPHITE_KEY_SUCC_NUM).' '.time()."\n"
                     .GRAPHITE_KEY_FAIL_NUM.' '.$redis->get(GRAPHITE_KEY_FAIL_NUM).' '.time()."\n"
                     ."\n";


            Log::info("send events \n". $events, 'send_events_to_graphite.log');
            $written = fwrite($fp, $events);
            if ($written == FALSE){
                Log::info('send fail !!!', 'send_events_to_graphite.log');
            }else{
                Log::info('sent '.$written .' bytes', 'send_events_to_graphite.log');
                $redis->delete(GRAPHITE_KEY_IMPRESSION_NUM, GRAPHITE_KEY_OPENAPP_NUM, GRAPHITE_KEY_SUCC_NUM, GRAPHITE_KEY_FAIL_NUM);
                Log::info('counts are reset to 0', 'send_events_to_graphite.log');
            }
            fclose($fp);
        }
    }

}
