<?php
/**
 * @开发工具: PhpStorm.
 * @文件名: IForm.class.php
 * @类功能: 表单填写辅助
 * @开发者: 小菜鸟
 * @开发时间: 15/5/21
 * @版本: version 1.0
 */
class IForm
{
    /**
     * @功能：select
     * @开发者：小菜鸟
     * @param $info
     * array(
     *  'field' => 'name',      字段名
     *  'remind'=> '选择联系人'   选择提示
     *  'options' => array(     下拉列表（k 值 => v 显示的名称)
     *      1 => 'kkk',
     *      2 => 'abc',
     *   ),
     *   'options_key' => 'k,v', //指定options中的每条数据的k，v
     *   'required' => true,
     *   'class' => 'my_select', class
     * @param null $default
     * @param bool $is_echo 是否输出
     * @return string
     */
    public static function select($info, $default = null, $is_echo = true)
    {
        $required = $info['required'] ? ' {min:0.01, messages:{min:"请选择一个"}} ' : '';
        $html = "<select style='height: 26px;width:{$info['width']}' id='{$info['field']}' name='{$info['field']}' class='{$info['class']} {$required}' >";
        list($opt_k, $opt_v) = explode(',', $info['options_key']);
        $opt_k = trim($opt_k);
        $opt_v = trim($opt_v);
        if ($info['remind']) {
            $html .= "<option value='0'>{$info['remind']}</option>";
        }
        foreach ($info['options'] as $k => $name) {
            if (is_array($name)) {
                $k = $name[$opt_k];
                $name = $name[$opt_v];
            }
            $select = ($default !== null && $default == $k) ? ' selected ' : '';
            $html .= "<option value='{$k}' {$select}> {$name}</option>";
        }
        $html = $html . '</select>';
        if ($is_echo) {
            echo $html;
        } else {
            return $html;
        }
    }

    /**
     * @功能：checkbox
     * @开发者：小菜鸟
     * @param $info
     * array(
     *  'field' => 'name',      字段名
     *  'options' => array(     下拉列表（k 值 => v 显示的名称)
     *      1 => 'kkk',
     *      2 => 'abc',
     *   ),
     *   'options_key' => 'k,v', //指定options中的每条数据的k，v
     *   'class' => 'my_select', class
     * @param null $default
     * @return string
     */
    public static function checkbox($info, $default = null)
    {
        $html = "<ul class='{$info['class']}'>";
        list($opt_k, $opt_v) = explode(',', $info['options_key']);
        foreach ($info['options'] as $k => $name) {
            if (is_array($name)) {
                $k = $name[trim($opt_k)];
                $name = $name[trim($opt_v)];
            }
            $select = (is_array($default) && in_array($k, $default)) ? ' checked ' : '';
            $html .= "<label style='margin-right: 2em;'>
			<input name=\"{$info['field']}[]\" class=\"ace ace-checkbox-2 father\" type=\"checkbox\" value=\"{$k}\"  {$select} />
			<span class=\"lbl\"> {$name}</span>
		    </label>";
        }

        echo $html.'</ul>';
    }
    /**
     * @功能：radio
     * @开发者：小菜鸟
     * @param $info
     * array(
     *  'field' => 'name',      字段名
     *  'options' => array(     下拉列表（k 值 => v 显示的名称)
     *      1 => 'kkk',
     *      2 => 'abc',
     *   ),
     *   'required' => true,
     *   'options_key' => 'k,v', //指定options中的每条数据的k，v
     *   'class' => 'my_select', class
     * @param null $default
     * @param bool $is_echo 是否输出
     * @return string
     */
    public static function radio($info, $default = null, $is_echo = true)
    {
        $required = $info['required'] ? ' {required:true, messages:{required:"请选择一个"}} ' : '';
        $html = '';
        list($opt_k, $opt_v) = explode(',', $info['options_key']);
        foreach ($info['options'] as $k => $name) {
            if (is_array($name)) {
                $k = $name[$opt_k];
                $name = $name[$opt_v];
            }
            $select   = ($default !== null && $default == $k) ? ' checked ' : '';
            $html .= "<input type='radio' name='{$info['field']}' {$required} value='{$k}' {$select} class='{$required}'/> {$name} &nbsp;&nbsp;";
        }
        if ($is_echo) {
            echo $html;
        } else {
            return $html;
        }
    }


    /**
     * @功能：图组上传，保存图组ID，用|隔开
     * @开发者：小菜鸟
     * @param $info
     * array(
     *  'field' => 'pic',
     *  'width' => 100,
     *  'height'=> 100,
     * )
     * @param $value : 默认值
     */
    public static function images($info, $value)
    {
        $info['width'] = $info['width'] ? $info['width'] : 450;
        $info['height']= $info['height'] ? $info['height'] : 100;
        BatchImage($info['field'], $info['width'], $info['height'], $value);
    }

    /**
     * @功能：单张图片
     * @开发者：小菜鸟
     * @param $info
     * array(
     *  'field' => 'pic',
     *  'width' => 100,
     *  'height'=> 100,
     * )
     * @param $value : 默认值
     */
    public static function image($info, $value)
    {
        $info['width'] = $info['width'] ? $info['width'] : 100;
        $info['height']= $info['height'] ? $info['height'] : 100;
        UpImage($info['field'], $info['width'], $info['height'], $value);
    }

    /**
     * @功能：普通输入框
     * @开发者：小菜鸟
     * @param $info : 配置项
     * array(
     *  'field' => 'name',
     *  'holder' => '密码',
     *  'remind' => 'kk'
     * )
     * @param $value : 默认值
     * @return string
     */
    public static function input($info, $value) {
        $str = "<input type=\"text\" name=\"{$info['field']}\" id=\"{$info['field']}\" placeholder=\"{$info['holder']}\"
                class=\"col-xs-10 col-sm-5\" value=\"{$value}\">
                <span class=\"help-inline col-xs-12 col-sm-7\">
                    <span class=\"middle\">{$info['remind']}</span>
                </span>";

        return $str;
    }

    /**
     * @功能：日期输入
     * @开发者：小菜鸟
     * @param $info
     * array(
     *  'field' => 'start_date',
     *  'class' => '',
     *  'fmt'   => '',
     * )
     * @param $value
     * @return string
     */
    public static function date($info, $value) {
        $str = "<input class=\"form-control date {$info['class']}\" id=\"{$info['field']}\" name=\"{$info['field']}\"
                value=\"{$value}\" type=\"text\" format='{$info['fmt']}'/>
                ";
        return $str;
    }

    /**
     * @功能：富文本输入
     * @开发者：小菜鸟
     * @param $field : 字段名
     * @param bool $is_simple : 是否简单版
     * @param $value : 默认值
     * @return string
     */
    public static function rich_text($field, $is_simple=false, $value='') {
        if ($is_simple) {
            $str = "<textarea name=\"{$field}\" id=\"{$field}\"
                    class=\"col-xs-10 col-sm-5 editor\" rows=\"5\">{$value}</textarea>
                    <script>
                        var edit_{$field}=KindEditor.create('#{$field}',{
                            resizeType : 1,
                            allowPreviewEmoticons : false,
                            allowImageUpload : false,
                            items : [
                                'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
                                'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
                                'insertunorderedlist', '|', 'emoticons','link']
                        });
                    </script>";
            return $str;
        } else {
            $str = "<textarea name=\"{$field}\" id=\"{$field}\"
                    class=\"col-xs-10 col-sm-5 editor\" rows=\"5\">{$value}</textarea>
                    <script>
                       var edit_{$field}=KindEditor.create('#content',{
                        uploadJson : '__PUBLIC__/kindeditor/php/upload_json.php',
                        fileManagerJson : '__PUBLIC__/kindeditor/php/file_manager_json.php',
                        allowFileManager : true,
                        afterBlur:function(){this.sync();}
                    });
                    </script>";
            return $str;
        }
    }
}