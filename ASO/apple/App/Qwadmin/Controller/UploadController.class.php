<?php
/**
 *
 * 版权所有：河马社区<www.hemashequ.com>
 * 作    者：小马哥<hanchuan@qiawei.com>
 * 日    期：2015-09-17
 * 版    本：1.0.3
 * 功能说明：文件上传控制器。
 *
 **/

namespace Qwadmin\Controller;
// 引入鉴权类
use Qiniu\Auth;
// 引入上传类
use Qiniu\Storage\UploadManager;

class UploadController extends ComController{
    /**
     * @功能：保存图片
     * @开发者：小菜鸟
     * @param $file
     * @return null|string
     */
    private function saveimg($file, $overwrite=false){
        import_org('qiniu/autoload.php');

        $max_file_size=1024*1024*10;     //上传文件大小限制, 单位BYTE
        $destination_folder='Public/attached/'.date('Ym').'/'; //上传文件路径
        if($max_file_size < $file["size"]){
            echo "文件太大!";
            return null;
        }

        if(!file_exists($destination_folder)){
            mkdir($destination_folder);
        }

        $filename = $file["tmp_name"];
        $ext_name = pathinfo($file["name"], PATHINFO_EXTENSION);

        $ext_list = [
            'jpeg', 'jpg', 'png','gif','bmp'
        ];

        if (!in_array($ext_name, $ext_list)) {
            echo "文件后缀名不符合";
            return null;
        }

        $destination = $destination_folder.time().mt_rand(1000,9999).".".$ext_name;

        if (file_exists($destination) && $overwrite != true){
            echo "同名文件已经存在了";
            return null;
        }

        if(!move_uploaded_file ($filename, $destination)) {
            return null;
        }

        return "/".$destination;
    }

    /**
     * @功能：kindeditor单文件上传接口
     * @开发者：小菜鸟
     */
    public function kind_upload_json() {
        if (!empty($_FILES['imgFile']['error'])) {
            switch($_FILES['imgFile']['error']){
                case '1':
                    $error = '超过php.ini允许的大小。';
                    break;
                case '2':
                    $error = '超过表单允许的大小。';
                    break;
                case '3':
                    $error = '图片只有部分被上传。';
                    break;
                case '4':
                    $error = '请选择图片。';
                    break;
                case '6':
                    $error = '找不到临时目录。';
                    break;
                case '7':
                    $error = '写文件到硬盘出错。';
                    break;
                case '8':
                    $error = 'File upload stopped by extension。';
                    break;
                case '999':
                default:
                    $error = '未知错误。';
            }
            exit(json_encode(['error' => 1, 'message' => $error]));
        }
        $file_name = $this->saveimg($_FILES['imgFile']);
        $url = C('qiniu_url').$file_name;
        exit(json_encode(['error' => 0, 'url' => $url]));
    }


    public function kind_manager_json() {
        exit('不支持的协议');
    }

    /**
     * @功能：单张图片上传
     * @开发者：小菜鸟
     */
    public function uploadpic(){
        $Img=I('Img');
        $Path=null;
        if($_FILES['img']){
            $Img=$this->saveimg($_FILES['img']);
        }
        $BackCall=I('BackCall');
        $Width=I('Width');
        $Height=I('Height');

        if(!$BackCall)$Width=$_POST['BackCall'];
        if(!$Width)$Width=$_POST['Width'];
        if(!$Height)$Width=$_POST['Height'];

        $this->assign('Width',$Width);
        $this->assign('BackCall',$BackCall);
        $this->assign('Img',$Img);
        $this->assign('Height',$Height);
        $this->display('Uploadpic');
    }

    /**
     * @功能：批量上传图片
     * @开发者：小菜鸟
     */
    public function batchpic(){
        $ImgStr = I('Img');
        $ImgStr = trim($ImgStr,'|');
        $Img = array();
        if(strlen($ImgStr)>1) {
            $Img=explode('|',$ImgStr);
        }
        $Path=null;

        if ($_FILES['img']){
            $filename=$this->saveimg($_FILES['img']);
            array_push($Img, $filename);
            $ImgStr=$ImgStr.'|'.$filename;
        }

        $BackCall=I('BackCall');
        $Width=I('u');
        $Height=I('Height');

        if(!$BackCall) {
            $Width=$_POST['BackCall'];
        }
        if(!$Width) {
            $Width=$_POST['Width'];
        }
        if(!$Height) {
            $Width=$_POST['Height'];
        }

        $this->assign('Width',$Width);
        $this->assign('BackCall',$BackCall);
        $this->assign('ImgStr',$ImgStr);
        $this->assign('Img',$Img);
        $this->assign('Height',$Height);
        $this->display('Batchpic');
    }
}
