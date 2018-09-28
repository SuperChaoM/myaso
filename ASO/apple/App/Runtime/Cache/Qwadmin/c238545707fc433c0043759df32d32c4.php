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

<!-- PAGE CONTENT BEGINS -->
<div class="row" style="padding-left: 10px;">
    <div style="text-align: center;font-size: 2rem">
        <?php echo ($work_info['work_name']); ?>--任务执行列表
    </div>
    <!--
    <form class="form-inline" action="" method="get">
        <label class="inline">人工编号:</label>
        <input type="text" name="device_num" class="form-control" value="<?php echo ($device_num); ?>">
       <label class="inline">任务状态:</label>
        <?php echo IForm::select(['field' =>'status', 'options'=>$status_name,'remind'=>'选择状态'],$status);?>
        <button type="submit" class="btn btn-purple btn-sm">
            <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
            搜索
        </button>
        <input type="hidden" name="work_id" value="<?php echo ($work_id); ?>">
        <input type="hidden" name="m" value="Qwadmin">
        <input type="hidden" name="c" value="Work">
        <input type="hidden" name="a" value="task_list">
         <span class="label label-success" style="float: right">已经执行<?php echo ($GLOBALS['__PAGE_COUNT__']); ?>个任务</span>
    </form>
    -->
</div>
<div class="space-4"></div>

<table class="table table-striped table-bordered table-hover">
    <thead>
    <tr>
        <th style="width: 70px;"  class="task">序号</th>
        <th style="width: 60px;"  class="task">账号ID</th>
        <th style="width: 120px;"  class="task">邮箱</th>
        <th style="width: 120px;"  class="task">密码</th>
        <th style="width: 60px;"  class="task">状态</th>
        <th style="width: 120px;"  class="task">领取时间</th>
        <th style="width: 120px;"  class="task">更新时间</th>
        </tr>
        </thead>
        <tbody class="task">
        <?php if(is_array($task_list)): $i = 0; $__LIST__ = $task_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$d): $mod = ($i % 2 );++$i;?><tr>
             <td><?php echo ($i+$row_offset); ?></td>
             <td><?php echo ($d['pos']); ?></td>
             <td><?php echo ($d['email']); ?></td>
            <td><?php echo ($d['password']); ?></td>
            <td>
                <?php if($d['status'] == 100): ?><span class="label label-success">成功</span><?php endif; ?>
                <?php if($d['status'] == 101): ?><span class="label label-warning">账号问题</span><?php endif; ?>
                <?php if($d['status'] == 102): ?><span class="label label-warning">验证码获取错误</span><?php endif; ?>
                <?php if($d['status'] == 103): ?><span class="label label-warning">验证码数据错误</span><?php endif; ?>
                <?php if($d['status'] == 104): ?><span class="label label-warning">打码错误</span><?php endif; ?>
                <?php if($d['status'] == 401): ?><span class="label label-warning">购买错误</span><?php endif; ?>
                <?php if($d['status'] == 403): ?><span class="label label-warning">登陆错误</span><?php endif; ?>
                <?php if($d['status'] == 408): ?><span class="label label-warning">已购买过此App</span><?php endif; ?>
                <?php if($d['status'] == 410): ?><span class="label label-warning">打码错误</span><?php endif; ?>
                <?php if($d['status'] == 1000): ?><span class="label label-warning">清理出错</span><?php endif; ?>
                <?php if($d['status'] == 1001): ?><span class="label label-warning">登录失败</span><?php endif; ?>
                <?php if($d['status'] == 1002): ?><span class="label label-warning">HOME失败</span><?php endif; ?>
                <?php if($d['status'] == 1003): ?><span class="label label-warning">搜索未命中</span><?php endif; ?>
                <?php if($d['status'] == 1004): ?><span class="label label-warning">搜索失败</span><?php endif; ?>
                <?php if($d['status'] == 1005): ?><span class="label label-warning">打开app详情失败</span><?php endif; ?>
                <?php if($d['status'] == 1006): ?><span class="label label-warning">准备购买参数</span><?php endif; ?>
                <?php if($d['status'] == 1007): ?><span class="label label-warning">设置密码错误</span><?php endif; ?>
                <?php if($d['status'] == 1008): ?><span class="label label-warning">购买失败</span><?php endif; ?>
                <?php if($d['status'] == 1009): ?><span class="label label-warning">二次购买</span><?php endif; ?>
                <?php if($d['status'] == 1010): ?><span class="label label-warning">下载错误</span><?php endif; ?>
                <?php if($d['status'] == 1011): ?><span class="label label-warning">XP事件错误</span><?php endif; ?>
                <?php if($d['status'] == 1012): ?><span class="label label-warning">无法连接Itunes</span><?php endif; ?>
                <?php if($d['status'] == 1013): ?><span class="label label-warning">改机后无法登陆</span><?php endif; ?>
                <?php if($d['status'] == 1014): ?><span class="label label-warning">账号锁定</span><?php endif; ?>
                <?php if($d['status'] == 2000): ?><span class="label label-warning">任务超时</span><?php endif; ?>
                <?php if($d['status'] == 2): ?><span class="label label-warning">失败</span><?php endif; ?>
                <?php if($d['status'] == 1): ?><span class="label label-success">成功</span><?php endif; ?>
                <?php if($d['status'] == 0): ?><span class="label">执行中</span><?php endif; ?>
            </td>
            <td><?php echo ($d['created_at']); ?></td>
            <td><?php echo ($d['updated_at']); ?></td>
        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    </tbody>
</table>
<?php echo ($page); ?>
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

<style>
    .task {
        vertical-align:middle !important;
        text-align: center
    }
</style>