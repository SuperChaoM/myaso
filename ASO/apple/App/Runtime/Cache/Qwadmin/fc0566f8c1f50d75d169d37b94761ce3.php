<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <title><?php echo ($current['title']); ?>-<?php echo (C("title")); ?></title>

    <meta name="keywords" content="<?php echo (C("keywords")); ?>" />
    <meta name="description" content="<?php echo (C("description")); ?>" />

    		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="/Public/qwadmin/css/bootstrap.css" />
		<link rel="stylesheet" href="/Public/qwadmin/css/font-awesome.css" />

		<!-- page specific plugin styles -->

		<!-- text fonts -->
		<link rel="stylesheet" href="/Public/qwadmin/css/ace-fonts.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="/Public/qwadmin/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="/Public/qwadmin/css/ace-part2.css" class="ace-main-stylesheet" />
		<![endif]-->

		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="/Public/qwadmin/css/ace-ie.css" />
		<![endif]-->

		<!-- inline styles related to this page -->

		<!-- ace settings handler -->
		<script src="/Public/qwadmin/js/ace-extra.js"></script>

		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

		<!--[if lte IE 8]>
		<script src="/Public/qwadmin/js/html5shiv.js"></script>
		<script src="/Public/qwadmin/js/respond.js"></script>
		<![endif]-->

    		<!-- basic scripts -->

		<!--[if !IE]> -->
		<script type="text/javascript">
			window.jQuery || document.write("<script src='/Public/qwadmin/js/jquery.js'>"+"<"+"/script>");
		</script>

		<!-- <![endif]-->

		<!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='/Public/qwadmin/js/jquery1x.js'>"+"<"+"/script>");
</script>
<![endif]-->
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='/Public/qwadmin/js/jquery.mobile.custom.js'>"+"<"+"/script>");
		</script>
		<script src="/Public/qwadmin/js/bootstrap.js"></script>

		<!-- page specific plugin scripts -->
		<script charset="utf-8" src="/Public/kindeditor/kindeditor-min.js"></script>
		<script charset="utf-8" src="/Public/kindeditor/lang/zh_CN.js"></script>
		<script src="/Public/qwadmin/js/bootbox.js"></script>
		<!-- ace scripts -->
		<script src="/Public/qwadmin/js/ace/elements.scroller.js"></script>
		<script src="/Public/qwadmin/js/ace/elements.colorpicker.js"></script>
		<script src="/Public/qwadmin/js/ace/elements.fileinput.js"></script>
		<script src="/Public/qwadmin/js/ace/elements.typeahead.js"></script>
		<script src="/Public/qwadmin/js/ace/elements.wysiwyg.js"></script>
		<script src="/Public/qwadmin/js/ace/elements.spinner.js"></script>
		<script src="/Public/qwadmin/js/ace/elements.treeview.js"></script>
		<script src="/Public/qwadmin/js/ace/elements.wizard.js"></script>
		<script src="/Public/qwadmin/js/ace/elements.aside.js"></script>
		<script src="/Public/qwadmin/js/ace/ace.js"></script>
		<script src="/Public/qwadmin/js/ace/ace.ajax-content.js"></script>
		<script src="/Public/qwadmin/js/ace/ace.touch-drag.js"></script>
		<script src="/Public/qwadmin/js/ace/ace.sidebar.js"></script>
		<script src="/Public/qwadmin/js/ace/ace.sidebar-scroll-1.js"></script>
		<script src="/Public/qwadmin/js/ace/ace.submenu-hover.js"></script>
		<script src="/Public/qwadmin/js/ace/ace.widget-box.js"></script>
		<script src="/Public/qwadmin/js/ace/ace.settings.js"></script>
		<script src="/Public/qwadmin/js/ace/ace.settings-rtl.js"></script>
		<script src="/Public/qwadmin/js/ace/ace.settings-skin.js"></script>
		<script src="/Public/qwadmin/js/ace/ace.widget-on-reload.js"></script>
		<script src="/Public/qwadmin/js/ace/ace.searchbox-autocomplete.js"></script>

		<script src="/Public/qwadmin/js/date-time/bootstrap-datepicker.js"></script>
		<script type="text/javascript">
			jQuery(function($){
				$(".date").each(function(i,e) {
					var format = $(e).attr('format') || 'yyyy-mm-dd';
					$(e).datepicker({
						format: format,
						weekStart: 1,
						autoclose: true,
						todayBtn: 'linked',
						language: 'cn'
					});
				});

			});

		</script>
		<script src="/Public/lightbox/js/lightbox.js"></script>
		<link rel="stylesheet" href="/Public/lightbox/css/lightbox.css"/>

</head>

<body class="no-skin">
<!-- #section:basics/navbar.layout -->
<div id="navbar" class="navbar navbar-default">
	<script type="text/javascript">
		try{ace.settings.check('navbar' , 'fixed')}catch(e){}
	</script>

	<div class="navbar-container" id="navbar-container">
		<!-- #section:basics/sidebar.mobile.toggle -->
		<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
			<span class="sr-only">Toggle sidebar</span>

			<span class="icon-bar"></span>

			<span class="icon-bar"></span>

			<span class="icon-bar"></span>
		</button>

		<!-- /section:basics/sidebar.mobile.toggle -->
		<div class="navbar-header pull-left">
			<!-- #section:basics/navbar.layout.brand -->
			<a href="<?php echo U('index/index');?>" class="navbar-brand">
				<small>
					<i class="fa fa-home"></i>
					<?php echo (C("title")); ?>-总后台
				</small>
			</a>

			<!-- /section:basics/navbar.layout.brand -->

			<!-- #section:basics/navbar.toggle -->

			<!-- /section:basics/navbar.toggle -->
		</div>

		<!-- #section:basics/navbar.dropdown -->
		<div class="navbar-buttons navbar-header pull-right" role="navigation">
			<ul class="nav ace-nav">
				<!-- #section:basics/navbar.user_menu -->
				<li class="light-blue">
					<a data-toggle="dropdown" href="#" class="dropdown-toggle">
						<span class="user-info">
							<small>欢迎光临，</small>
							<?php echo ($user_info["user"]); ?>
						</span>
						<i class="ace-icon fa fa-caret-down"></i>
					</a>

					<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
						<li>
							<a href="<?php echo U('Setting/setting');?>">
								<i class="ace-icon fa fa-cog"></i>
								系统设置
							</a>
						</li>

						<li>
							<a href="<?php echo U('Personal/profile');?>">
								<i class="ace-icon fa fa-user"></i>
								个人资料
							</a>
						</li>

						<li class="divider"></li>

						<li>
							<a href="<?php echo U('logout/index');?>">
								<i class="ace-icon fa fa-power-off"></i>
								退出
							</a>
						</li>
					</ul>
				</li>

				<!-- /section:basics/navbar.user_menu -->
			</ul>
		</div>

		<!-- /section:basics/navbar.dropdown -->
	</div><!-- /.navbar-container -->
</div>

<!-- /section:basics/navbar.layout -->
<div class="main-container" id="main-container">
    <script type="text/javascript">
        try{ace.settings.check('main-container' , 'fixed')}catch(e){}
    </script>

    <!-- #section:basics/sidebar -->
			<div id="sidebar" class="sidebar responsive">
				<script type="text/javascript">
					try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
				</script>

				<ul class="nav nav-list">
					<?php if(is_array($menu)): $i = 0; $__LIST__ = $menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li <?php if(($v['id'] == $current['id']) OR ($v['id'] == $current['pid']) OR ($v['id'] == $current['ppid'])): ?>class="active  <?php if($current['pid'] != '0'): ?>open<?php endif; ?>"<?php endif; ?>>
						<a href="<?php if(empty($v["name"])): ?>#<?php else: echo U($v['name']); endif; ?>" <?php if(!empty($v["children"])): ?>class="dropdown-toggle"<?php endif; ?>>
							<i class="<?php echo ($v["icon"]); ?>"></i>
							<span class="menu-text">
								<?php echo ($v["title"]); ?>
							</span>
							<?php if(!empty($v["children"])): ?><b class="arrow fa fa-angle-down"></b><?php endif; ?>
						</a>

						<b class="arrow"></b>
						<?php if(!empty($v["children"])): ?><ul class="submenu">
							<?php if(is_array($v["children"])): $i = 0; $__LIST__ = $v["children"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($i % 2 );++$i;?><li <?php if(($vv['id'] == $current['id']) OR ($vv['id'] == $current['pid'])): ?>class="active <?php if($current['ppid'] != '0'): ?>open<?php endif; ?>"<?php endif; ?>>
								<a href="<?php if(empty($vv["children"])): echo U($vv['name']); else: ?>#<?php endif; ?>" <?php if(!empty($vv["children"])): ?>class="dropdown-toggle"<?php endif; ?>>
									<i class="<?php echo ($vv["icon"]); ?>"></i>
									<?php echo ($vv["title"]); ?>
									<?php if(!empty($vv["children"])): ?><b class="arrow fa fa-angle-down"></b><?php endif; ?>
								</a>

								<b class="arrow"></b>
								<?php if(!empty($vv["children"])): ?><ul class="submenu">
									<?php if(is_array($vv["children"])): $i = 0; $__LIST__ = $vv["children"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vvv): $mod = ($i % 2 );++$i;?><li <?php if($vvv['id'] == $current['id']): ?>class="active"<?php endif; ?>>
											<a href="<?php echo U($vvv['name']);?>">
												<i class="<?php echo ($vvv["icon"]); ?>"></i>
												<?php echo ($vvv["title"]); ?>
											</a>
											<b class="arrow"></b>
										</li><?php endforeach; endif; else: echo "" ;endif; ?>
									</ul><?php endif; ?>
							</li><?php endforeach; endif; else: echo "" ;endif; ?>
						</ul><?php endif; ?>
					</li><?php endforeach; endif; else: echo "" ;endif; ?>
					
				</ul><!-- /.nav-list -->

				<!-- #section:basics/sidebar.layout.minimize -->
				<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
					<i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
				</div>

				<!-- /section:basics/sidebar.layout.minimize -->
				<script type="text/javascript">
					try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
				</script>
			</div>

			<!-- /section:basics/sidebar -->
    <div class="main-content">
        <div class="main-content-inner">
            <div class="breadcrumbs" id="breadcrumbs">
                <script type="text/javascript">
                    try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
                </script>

                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <a href="<?php echo U('index/index');?>">首页</a>
                    </li>
                    <?php if($current['ptitle']): ?><li>
                        <?php echo ($current['ptitle']); ?>
                    </li><?php endif; ?>
                    <li class="active"><?php echo ($current['title']); ?></li>
                </ul><!-- /.breadcrumb -->
            </div>
            <div class="page-content">
                <!-- #section:settings.box -->
						<?php if($current["tips"] != ''): ?><div class="alert alert-block alert-success">
							<button type="button" class="close" data-dismiss="alert">
								<i class="ace-icon fa fa-times"></i>
							</button>
							<!--i class="ace-icon fa fa-check green"></i-->
							<?php echo ($current["tips"]); ?>
						</div><?php endif; ?>

                <!-- /section:settings.box -->
                <div class="row">
                    <div class="col-xs-12">
                        <!-- PAGE CONTENT BEGINS -->
                        <!-- PAGE CONTENT ENDS -->

<div style="text-align: center;font-size: 2rem">
	<?php echo ($work_info['work_name']); ?>--工作计划 [<span id="sum_num" style="margin: 0 5px;">0 成功数</span>]
</div>
<!-- PAGE CONTENT BEGINS -->
<form class="form-horizontal" role="form" action="<?php echo U('save_plain');?>" method="post">
<div class="container-fluid">
	<div class="row" style="margin-bottom: 1rem;">
		<div class="col-xs-4 col-sm-2">
			<input type="hidden" name="work_id" value="<?php echo ($work_info['work_id']); ?>">
			<input type="submit" class="btn btn-purple" value="保存工作计划">
		</div>

		<div class="col-xs-4 col-sm-2">
			<div class="btn btn-success" id="clear_all">全部清零</div>
		</div>
		<div class="col-xs-4 col-sm-2">
			<div class="btn btn-primary" id="set_first">设置和首个任务一样</div>
		</div>
		<div class="col-xs-4 col-sm-2">
		</div>
	</div>
	<div class="row" style="margin-bottom: 1rem;">
		<div id="visit" style="width: 900px;height:250px;"></div>
	</div>
	<div class="row" id="plain_list">

	</div>
</div>
</form>
<div style="display: none">
	<div class="col-xs-6 col-sm-3" id="task_tpl">
		<table class="table table-bordered"><tr>
			<td><input type="number" class="task_num" placeholder="输入任务数量"></td></tr>
			<tr>
				<td>
					<div style="text-align: center" class="time">00:00</div>
				</td>
			</tr></table>
	</div>
</div>
<style>
	.task_num {
		width: 100%;
	}
</style>
<script>
	function insert_plain(time, num, index) {
		var $plain = $("#task_tpl").clone().attr('id', index);
		$plain.find('.time').text(time);
		$plain.find('.task_num').attr('name', 'plain['+time+']').val(num);
		$("#plain_list").append($plain);
	}
	work_plain = <?php echo ($work_plain); ?>;
	var index = 0;
	for(var t in work_plain) {
		var num = work_plain[t];
		insert_plain(t, num, index);
		index++;
	}

	$("#clear_all").click(function() {
		$(".task_num").val('');
		$(".task_num").change();
	});
	$("#set_first").click(function() {
		$(".task_num").val($("#0 .task_num").val());
		$(".task_num").change();
	});

	$(".task_num").change(function() {
		var sum_num = 0;
		$(".task_num").each(function(i,e) {
			var $e = $(e);
			var v  = $e.val();
			if (v == '') {
				v = 0;
			} else {
				v = parseInt(v);
			}
			sum_num += v;
		});
		$("#sum_num").html(sum_num + '成功数');
	});
	$(function() {
		$(".task_num").change();
	});
</script>
<!-- PAGE CONTENT ENDS -->
</div><!-- /.col -->
</div><!-- /.row -->
</div><!-- /.page-content -->
</div>
</div><!-- /.main-content -->
			<div class="footer">
				<div class="footer-inner">
					<!-- #section:basics/footer -->
					<div class="footer-content">
						<span class="bigger-120">
							<small><?php echo (C("footer")); ?> All Rights Reserved.</small>
						</span>
					</div>

					<!-- /section:basics/footer -->
				</div>
			</div>

			<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
				<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
			</a>
			<script src="/Public/qwadmin/js/validform.js"></script>
			<link rel="stylesheet" href="/Public/qwadmin/css/validform.css" />

</div><!-- /.main-container -->

<!-- inline scripts related to this page -->
<script src="/Public/qwadmin/js/date-time/bootstrap-datepicker.js"></script>
<script type="text/javascript">
    jQuery(function($){
        $('#birthday').datepicker({
            format: 'yyyy-mm-dd',
            weekStart: 1,
            autoclose: true,
            todayBtn: 'linked',
            language: 'cn'
        });
    });

</script>
</body>
</html>

<script src="/Public/qwadmin/js/echarts.min.js"></script>
<script type="text/javascript">
	// 基于准备好的dom，初始化echarts实例
	var myChart = echarts.init(document.getElementById('visit'));

	// 指定图表的配置项和数据
	var option = {
		tooltip: {
			trigger: 'axis'
		},
		legend: {
			data:['成功数']
		},
		grid: {
			containLabel: true
		},
		xAxis: {
			type: 'category',
			boundaryGap: false,
			data: <?php echo ($plain_x); ?>
		},
		yAxis: {
			type: 'value'
		},
		series: [
			{
				name:'成功数',
				type:'line',
				stack: '总量',
				data:<?php echo ($plain_y); ?>
			}
		]
	};
	// 使用刚指定的配置项和数据显示图表。
	myChart.setOption(option);
</script>