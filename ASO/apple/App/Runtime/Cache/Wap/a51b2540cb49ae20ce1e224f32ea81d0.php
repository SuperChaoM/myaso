<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
	<meta name="format-detection" content="telephone=no">
	<title>真人投票</title>
	<link rel="stylesheet" href="/Public/frozen/css/frozen.css">
	<script src="/Public/frozen/lib/zepto.min.js"></script>
	<script src="/Public/frozen/js/frozen.js"></script>
</head>

<body>
<section class="ui-container">
    <div>
        <img src="/Public/wap/img/banner1_2.png" style="width: 100%">
    </div>
    <div>
        <img src="/Public/wap/img/banner2_2.png" style="width: 100%">
    </div>

    <div class="ui-form ui-border-t">
        <form action="#">
            <div class="ui-form-item ui-form-item-l ui-border-b">
                <label class="ui-border-r">
                    投票目标
                </label>
                <input type="text" placeholder="请输入投票目标" name="user_name"  id="user_name">
            </div>
            <div class="ui-form-item ui-form-item-l ui-border-b">
                <label class="ui-border-r">
                    投票数量
                </label>
                <div class="ui-select" style="text-align: center;padding-left: 15%;">
                    <select style="width: 100%" name="vote_num" id="vote_num">
                        <option>100票</option>
                        <option>200票</option>
                        <option>500票</option>
                        <option>1000票</option>
                        <option>2000票</option>
                        <option>5000票</option>
                    </select>
                </div>
            </div>
            <div class="ui-form-item ui-form-item-l ui-border-b">
                <label class="ui-border-r">
                    投票网址
                </label>
                <input type="text" placeholder="以http://或者https://打头" name="vote_url"  id="vote_url">
            </div>
            <div class="ui-form-item ui-form-item-l ui-border-b">
                <label class="ui-border-r">
                    联系电话
                </label>
                <input type="number" placeholder="方便联系,以便尽快安排投票" name="user_phone"  id="user_phone">
            </div>
        </form>
    </div>

    <div class="ui-btn-wrap">
        <button class="ui-btn-lg ui-btn-primary" id="submit">
            立即下单
        </button>
    </div>

    <div>
        <div class="ui-flex ui-flex-pack-center">
            <div>联系客服(长按二维码)</div>
        </div>
        <div style="text-align: center">
            <img src="/Public/wap/img/qrcode.png" style="width: 50%">
        </div>
    </div>
    <div style="width: 100%;height: 1rem;"></div>
</section>
<script>
    var vote_submit_url = "<?php echo U('vote_submit');?>";
    $("#submit").click(function() {
        var user_name = $.trim($("#user_name").val());
        var vote_url  = $.trim($("#vote_url").val());
        var user_phone= parseInt($.trim($("#user_phone").val()));
        var vote_num  = $.trim($("#vote_num").val());

        if (user_name=='') {
            var dia=$.dialog({
                title:'温馨提示',
                content:'请填写投票目标',
                button:["确认"]
            });
            return;
        }

        if (vote_url=='' || vote_url.indexOf('http') === false) {
            var dia=$.dialog({
                title:'温馨提示',
                content:'请填写以http://或者https://打头的合法投票地址',
                button:["确认"]
            });
            return;
        }
        if (isNaN(user_phone) || user_phone < 10000000000 || user_phone > 100000000000) {
            var dia=$.dialog({
                title:'温馨提示',
                content:'请填写正确的手机号码',
                button:["确认"]
            });
            return;
        }

        var el=$.loading({
            content:'下单中...',
        });
        $.post(vote_submit_url, {
            vote_num:vote_num,
            user_phone:user_phone,
            vote_url:vote_url,
            user_name:user_name
        }, function(result) {
            el.loading("hide");
            if (result.ret == 0) {
                var dia=$.dialog({
                    title:'成功提示',
                    content:'下单成功,请马上联系下面的微信客服安排投票',
                    button:["确认"]
                });
            } else {
                var dia=$.dialog({
                    title:'出错提示',
                    content:result.msg,
                    button:["确认"]
                });
            }
        },'json');
    });
</script>
</body>
</html>