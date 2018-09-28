<?php
namespace Api\Controller;

use Think\Log;

class AppleController extends AppleBaseController{
    public function get_free_app_id_list() {
        $max_id = M('download')->field('id')->order('id desc')->getField('id');
        $where  = ['pos' => $max_id];
        $app_list = M('app_pos')->where($where)->select();
        $app_id_list = array_column($app_list, 'app_id');
        return $app_id_list;
    }

    public function update_download_task() {
        if ($_REQUEST['pw'] !== self::PASSWORD) {
            $this->error('error token');
        }

        $m_work = M('work');
        $config = ['work_id' => self::NUMBER, 'succ_num' => self::NUMBER, 'fail_num' => self::NUMBER,'type' => self::NUMBER, 'account_id' => self::NUMBER];
        $param = $this->get_param($config);

        // check cache first
        $redis = get_redis();
        $cache_key = WORK_TASK_STATS . '-' . $param['work_id'];
        if (!$redis->exists($cache_key)){
            $where = ['work_id' => $param['work_id']];
            $data  = $m_work->where($where)->field('impression_num, openapp_num, succ_num, fail_num')->find();
            $redis->hIncrBy($cache_key, 'impression_num', $data['impression_num']);
            $redis->hIncrBy($cache_key, 'openapp_num', $data['openapp_num']);
            $redis->hIncrBy($cache_key, 'succ_num', $data['succ_num']);
            $redis->hIncrBy($cache_key, 'fail_num', $data['fail_num']);
            Log::info('init stats nums for work '. $param['work_id'].' '.$data['impression_num'].'/'.$data['openapp_num'].'/'.$data['succ_num'].'/'.$data['fail_num']);
        }

        // update work item status
        if ($param['account_id'] > 0){
            $status = 0;
            if ($param['fail_num']>0){
                 $status = 2;
            }
            if ($param['succ_num']>0){
                $status = 1;
            }
            if (isset($_REQUEST['ret'])){
                $task_ret = $_REQUEST['ret'];
                $status = $task_ret;
                //401---账号登陆错误
                if(intval($task_ret) == 1014){
                    $this->update_account_forbidden($param['account_id']);
                }
            }
            $m_work_item = M('work_item');
            $where = [ 'work_id' => $param['work_id'], 'pos' => $param['account_id']];
            $item_data = [ 'status'=> $status , 'updated_at'=>date('Y-m-d H:i:s') ];
            $m_work_item->where($where)->save($item_data);
        }

        if ($param['succ_num'] > 0){
            switch ($param['type']) {
                case 1:
                {
                   // 曝光+1
                    $redis->hIncrBy($cache_key, 'impression_num', 1);
                    $redis->incr(GRAPHITE_KEY_IMPRESSION_NUM);
                    $this->success('曝光-成功');
                }
                    break;

                case 2:
                {
                   // 展示+1
                    $redis->hIncrBy($cache_key, 'openapp_num', 1);
                    $redis->incr(GRAPHITE_KEY_OPENAPP_NUM);
                    $this->success('展示-成功');
                }
                    break;

                case 3:
                {
                  // 下载+1
                    $redis->hIncrBy($cache_key, 'succ_num', 1);
                    $redis->incr(GRAPHITE_KEY_SUCC_NUM);
                    $this->success('下载-成功');
                }
                    break;

                default:

                    break;
            }
        }

        if ($param['fail_num'] > 0){
            // 失败+1
            $redis->hIncrBy($cache_key, 'fail_num', 1);
            $redis->incr(GRAPHITE_KEY_FAIL_NUM);
            $this->success('类型'.$param['type'].'-失败+1');
        }

    }

    public function update_zh_download_task() {

        $m_work = M('work');
        //$config = ['work_id' => self::NUMBER, 'succ_num' => self::NUMBER, 'fail_num' => self::NUMBER,'type' => self::NUMBER, 'account_id' => self::NUMBER];
        //$param = $this->get_param($config);
        $param = ['work_id'=>0];
        $postJson = file_get_contents('php://input');
        $postContent = json_decode($postJson);
        $taskInfo = $postContent->data;
        $param['work_id'] = current($taskInfo)->a_id;
        if($postContent->status == 200){
          $param['succ_num'] = 1;
          $param['fail_num'] = 0;
        }
        else{
          $param['succ_num'] = 0;
          $param['fail_num'] = 1;
        }
        $param['type'] = 3;
        // check cache first
        $redis = get_redis();
        $cache_key = WORK_TASK_STATS . '-' . $param['work_id'];
        if (!$redis->exists($cache_key)){
            $where = ['work_id' => $param['work_id']];
            $data  = $m_work->where($where)->field('impression_num, openapp_num, succ_num, fail_num')->find();
            $redis->hIncrBy($cache_key, 'impression_num', $data['impression_num']);
            $redis->hIncrBy($cache_key, 'openapp_num', $data['openapp_num']);
            $redis->hIncrBy($cache_key, 'succ_num', $data['succ_num']);
            $redis->hIncrBy($cache_key, 'fail_num', $data['fail_num']);
            Log::info('init stats nums for work '. $param['work_id'].' '.$data['impression_num'].'/'.$data['openapp_num'].'/'.$data['succ_num'].'/'.$data['fail_num']);
        }
        if ($param['succ_num'] > 0){
            switch ($param['type']) {
                case 1:
                {
                   // 曝光+1
                    $redis->hIncrBy($cache_key, 'impression_num', 1);
                    $redis->incr(GRAPHITE_KEY_IMPRESSION_NUM);
                }
                    break;

                case 2:
                {
                   // 展示+1
                    $redis->hIncrBy($cache_key, 'openapp_num', 1);
                    $redis->incr(GRAPHITE_KEY_OPENAPP_NUM);
                }
                    break;

                case 3:
                {
                  // 下载+1
                    $redis->hIncrBy($cache_key, 'succ_num', 1);
                    $redis->incr(GRAPHITE_KEY_SUCC_NUM);
                }
                    break;

                default:

                    break;
            }
        }

        if ($param['fail_num'] > 0){
            // 失败+1
            $redis->hIncrBy($cache_key, 'fail_num', 1);
            $redis->incr(GRAPHITE_KEY_FAIL_NUM);
        }

        $zh_data = ['reason'=>'反馈成功',result=>true,'work_id'=>$param['work_id']];
        $this->serializeJson($zh_data);

    }

    public function update_account_forbidden($account_id) {
        $m_account = M('download');
        $where = ['id' => $account_id];
        $data  = $m_account->where($where)->field('id, status')->find();
        $data = ['status' => \AccountStatus::FORBIDDEN];
        $m_account->where($where)->save($data);
    }

    public function update_account_status() {
        if ($_REQUEST['pw'] !== self::PASSWORD) {
            $this->error('error token');
        }

        if (intval($_REQUEST['status']) !== \AccountStatus::FORBIDDEN) {
            // 暂时只处理封禁账号
            $this->success('no need to update.');
        }

        $config = ['account_id' => self::NUMBER, 'status' => self::NUMBER];
        $param = $this->get_param($config);
        $m_account = M('download');
        $where = ['id' => $param['account_id']];
        $data  = $m_account->where($where)->field('id, status')->find();
        $data = ['status' => \AccountStatus::FORBIDDEN];
        $m_account->where($where)->save($data);
        $this->success('更新成功');
    }

    /**
     * @功能：获取混合任务
     */
    public function get_mixed_task() {
        if ($_REQUEST['pw'] !== self::PASSWORD) {
            $this->error('error token');
        }


        $data_list = [];
        $redis = get_redis();
        $task_max_num = C('task_max_num');

        $m_work_item = M('work_item');
        $m_work_item->startTrans();
        for ($i=0; $i< $task_max_num; ++$i) {
            $data = $redis->rPop(WORK_TASK_LIST);
            if ($data) {
                $data_list[] = $data;
                $item = [
                    'work_id' => $data['work_id'],
                    'pos'=> $data['account_id'],
                    'email'=>$data['email'],
                    'password'=>$data['password'],
                    'created_at'=>date('Y-m-d H:i:s'),
                    'updated_at'=>date('Y-m-d H:i:s') ];
                $m_work_item->add($item);
            }
        }
        $m_work_item->commit();
        Log::info("get_num:".count($data_list));

        if (empty($data_list)) {
            $this->error('暂时没有任务');
        }
        $this->success($data_list);
    }

    //测试别人家的插件
    public function get_zh_mixed_task() {

        $zh_data = ['data_count'=>1,'status'=>100,'thread'=>1,'devicetype'=>'iPhone'];
        $redis = get_redis();
        $m_work_item = M('work_item');
        $m_work_item->startTrans();
        $data = $redis->rPop(WORK_TASK_LIST);
        if ($data) {
            $task_data = [
                'work_id' => $data['work_id'],
                'pos'=> $data['account_id'],
                'email'=>$data['email'],
                'password'=>$data['password'],
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s'),
                'a_id' => $data['work_id'],
                'c_id' => $data['account_id'],
                'account_id'=>$data['email'],
                'password'=>$data['password'],
                'HardwarePlatform'=>$data['hardware_platform'],
                'serial'=>$data['serial'],
                'BuildVersion'=>$data['build_version'],
                'BluetoothAddress'=>$data['bt'],
                'ProductType'=>$data['product_type'],
                'idfa'=>$data['idfa'],
                'HardwareModel'=>$data['hardware_model'],
                'ecid'=>$data['ecid'],
                'imei'=>$data['imei'],
                'reason'=> NULL,
                'state'=>3,
                'WiFiAddress' => $data['wifi'],
                'udid' => $data['udid'],
                'ProductVersion' => $data['product_version'],
                'num' => 9,
                'unknown' => NULL];
              $zh_data['data'] = array($task_data);
              $zh_appdata = ['appid'=>$data['app_id'],'appkey'=>$data['keyword'],'unknown'=>""];
              $zh_data['appdata'] = $zh_appdata;
              $item = [
                  'work_id' => $data['work_id'],
                  'pos'=> $data['account_id'],
                  'email'=>$data['email'],
                  'password'=>$data['password'],
                  'created_at'=>date('Y-m-d H:i:s'),
                  'updated_at'=>date('Y-m-d H:i:s') ];
              $m_work_item->add($item);
            }else{
                $this->serializeJson(['data_count'=>0]);
          }
        $m_work_item->commit();
        /*Log::info("get_num:".count($data_list));

        if (empty($data_list)) {
            $this->error('暂时没有任务');
        }*/
        $this->serializeJson($zh_data);
    }


    /**
     * @功能：获取协议客户端配置信息
     */
    public function get_app_config() {
        if ($_REQUEST['pw'] !== self::PASSWORD) {
            $this->error('error token');
        }

        $app_thread_count = intval(C('app_thread_count'));
        $app_task_timeout = intval(C('app_task_timeout'));
        $app_impressions = floor(floatval(C('app_impressions')) * 100);
        $app_product_page_views = floor(floatval(C('app_product_page_views')) * 100);
        $app_downloads = floor(floatval(C('app_downloads')) * 100);

        if ($app_thread_count <= 0
            || $app_task_timeout <= 0
            || $app_impressions <= 0
            || $app_product_page_views <= 0
            || $app_downloads <= 0){
            $this->error('请管理员到后台检查自定义配置');
        }

        $app_config = array(
            'app_thread_count' => $app_thread_count,
            'app_task_timeout' => $app_task_timeout,
            'app_impressions' => $app_impressions,
            'app_product_page_views' => $app_product_page_views,
            'app_downloads' => $app_downloads,
            );
        $this->success($app_config);
    }

}
