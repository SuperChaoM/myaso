<?php
/**
 * IExcel - excel 文件下载处理类
 * 注意：
 * 1，全局编码为UTF-8,否则可能出错
 * 2，支持读写excel2003,2007
 * 3，csv需要的时候再实现
 * @author wenyouming
 * @since  2014-01-14
 * @version 1.0
 *
 */

class IExcel {
    /**
     * 文件下载
     * @param  array $data :数组数据
     * @param  string $filename :文件名称
     * @param  boolean $is_down :是否下载
     * @param int $version
     * @param bool $is_big
     * @return bool
     */
    private static function write($data, $filename = 'export', $is_down = true, $version=2007, $is_big = false) {
        /* 写excel需要较大的内存开销 */
        if ($is_big) {
            $memory = '512M';
        } else {
            $memory = '256M';
        }
        ini_set('memory_limit', $memory);

        /* 数据维度检测 */
        $depth = array_depth($data);
        if ($depth <= 0 || $depth >= 4) {
            echo "depth:$depth. 超过预定范围";
            return false;
        }
        /* 维度补齐到3维数组 */
        if ($depth == 1) {
            $data = array(array($data));
        } elseif($depth == 2) {
            $data = array($data);
        }
        include_once __DIR__.'/PHPExcel/PHPExcel.class.php';
        $excel = new PHPExcel();

        $index = 0;
        foreach ($data as $sheet_data) {
            if ($index) {
                $excel->createSheet();
            }
            $excel->setActiveSheetIndex($index++);
            $sheet = $excel->getActiveSheet();

            $i = 1;
            foreach ($sheet_data as $row) {
                $j = 'A';
                foreach ($row as $v) {
                    /* 以字符串写入 */
                    $sheet->setCellValueExplicit($j.$i, $v, PHPExcel_Cell_DataType::TYPE_STRING);
                    ++$j;
                }
                ++$i;
            }
        }

        if ($version == 2003) {
            $filename .= '.xls';
            $writer = new PHPExcel_Writer_Excel5($excel);
        } else {
            $filename .= '.xlsx';
            $writer= new PHPExcel_Writer_Excel2007($excel);
        }

        if (!$is_down) {
            try {
                $writer->save($filename);
            } catch (PHPExcel_Writer_Exception $e) {
                echo $e->getMessage();
                return false;
            }
            return true;
        }

        /* 如果是IE,进行转码,防止乱码 */
        if (strpos($_SERVER["HTTP_USER_AGENT"],"MSIE")) {
            $filename = rawurlencode($filename);
        }

        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header("Content-Disposition:attachment;filename=\"$filename\"");
        header("Content-Transfer-Encoding:binary");
        $writer->save('php://output');

        return true;
    }

    /**
     * write_2007 - 写2007格式
     * @param array $data	: 数据
     * @param string $filename : 文件名
     * @param boolean $is_big  : 是否写大文件，默认false
     */
    static function write_2007($data, $filename='export_2007', $is_big = false) {
        self::write($data, $filename, false, 2007, $is_big);
    }
    /**
     * down_2007 - 下载2007文件格式
     * @param array $data	: 数据
     * @param string $filename : 文件名
     * @param boolean $is_big  : 是否写大文件，默认false
     */
    static function down_2007($data, $filename='export_2007', $is_big = false) {
        self::write($data, $filename, true, 2007, $is_big);
    }
    /**
     * write_2003 - 写2003格式
     * @param array $data	: 数据
     * @param string $filename : 文件名
     * @param boolean $is_big  : 是否写大文件，默认false
     */
    static function write_2003($data, $filename='export_2003', $is_big = false) {
        self::write($data, $filename, false, 2003, $is_big);
    }
    /**
     * down_2003 - 下载2003文件格式
     * @param array $data	: 数据
     * @param string $filename : 文件名
     * @param boolean $is_big  : 是否写大文件，默认false
     */
    static function down_2003($data, $filename='down_2003', $is_big = false) {
        self::write($data, $filename, true, 2003, $is_big);
    }

    /**
     * read - 读取excel文件（自动识别excel2007和excel2003)
     * 推荐文件只用字符串格式，或者数字，其他格式需要检查是否有误
     * @param  string $filename 文件名
     * @param bool $multi_sheet 是否多个sheet
     * @param  boolean $is_big 是否为大文件
     * @return mixed -1,文件不存在; -2,不能识别的文件; array, 3维数组
     */
    static function read($filename, $multi_sheet = false, $is_big = false) {
        if (!file_exists($filename)) {
            return -1;
        }

        if ($is_big) {
            $memory = '1024M';
        } else {
            $memory = '256M';
        }
        ini_set('memory_limit', $memory);

        include_once __DIR__.'/PHPExcel/PHPExcel.class.php';
        $reader = new PHPExcel_Reader_Excel2007();
        if (!$reader->canRead($filename)) {
            $reader = new PHPExcel_Reader_Excel5();
            if (!$reader->canRead($filename)) {
                return -2;
            }
        }

        $excel = $reader->load($filename);
        $sheet_list = $excel->getAllSheets();
        $data = array();
        foreach ($sheet_list as $sheet) {
            $sheet_data = $sheet->toArray();
            $data[] = $sheet_data;
        }

        return $multi_sheet ? $data : $data[0];
    }

}
