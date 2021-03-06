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
	<form class="form-horizontal" action="<?php echo U('update');?>" method="post">
	<div class="form-group">
		<label class="col-sm-1 control-label no-padding-right" for="form-field-10"> 上级菜单 </label>
		<input name="id" value="<?php echo ($currentmenu["id"]); ?>" type="hidden">
		<div class="col-sm-9">
		<select id="pid" name="pid" class="rcol-xs-10 col-sm-5">
				<option value="0" <?php if($currentmenu["pid"] == 0): ?>selected="selected"<?php endif; ?>>顶级菜单</option>
			<?php if(is_array($option)): $i = 0; $__LIST__ = $option;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["id"]); ?>"  <?php if($currentmenu["pid"] == $v['id']): ?>selected="selected"<?php endif; ?>><?php echo ($v['title']); ?></option>
				<?php if(is_array($v["children"])): $i = 0; $__LIST__ = $v["children"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vv["id"]); ?>" <?php if($currentmenu["pid"] == $vv['id']): ?>selected="selected"<?php endif; ?>>&nbsp;&nbsp;┗━<?php echo ($vv['title']); ?></option><?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
		</select>
		<span class="help-inline col-xs-12 col-sm-7">
				<span class="middle"></span>
			</span>
		</div>
	</div>
	<div class="space-4"></div>
	<div class="form-group">
		<label class="col-sm-1 control-label no-padding-right" for="form-field-1"> 菜单名称 </label>
		<div class="col-sm-9">
			<input type="text" name="title" id="title" class="rcol-xs-10 col-sm-5" value="<?php echo ($currentmenu["title"]); ?>">
			<span class="help-inline col-xs-12 col-sm-7">
				<span class="middle"></span>
			</span>
		</div>
	</div>

	<div class="space-4"></div>
	<div class="form-group">
		<label class="col-sm-1 control-label no-padding-right" for="form-field-2"> 链接 </label>
		<div class="col-sm-9">
			<input type="text" name="name" id="name" placeholder="链接，如：Index/index" class="col-xs-10 col-sm-5" value="<?php echo ($currentmenu["name"]); ?>">
			<span class="help-inline col-xs-12 col-sm-7">
				<span class="middle"></span>
			</span>
		</div>
	</div>

	<div class="space-4"></div>
	<div class="form-group">
		<label class="col-sm-1 control-label no-padding-right" for="form-field-2"> ICON图标 </label>
		<div class="col-sm-9">
			<input type="text" name="icon" id="icon" placeholder="ICON图标" class="col-xs-10 col-sm-5" value="<?php echo ($currentmenu["icon"]); ?>">
			<span class="help-inline col-xs-12 col-sm-7">
				<span class="middle"></span>
			</span>
		</div>
	</div>
	<div class="space-4"></div>
	<div class="form-group">
		<label class="col-sm-1 control-label no-padding-right" for="form-field-2"> 显示状态 </label>
		<div class="control-label no-padding-left col-sm-1">
			<label>
				<input name="islink" id="islink" <?php if($currentmenu["islink"] == 1): ?>checked="checked"<?php endif; ?> value="1" class="ace ace-switch ace-switch-2" type="checkbox" />
				<span class="lbl"></span>
			</label>
		</div>
		<span class="help-inline col-xs-12 col-sm-7">
				<span class="middle"></span>
		</span>
	</div>
	<div class="space-4"></div>
	<div class="form-group">
		<label class="col-sm-1 control-label no-padding-right" for="form-field-2"> 排序 </label>
		<div class="col-sm-9">
			<input type="text" name="o" id="o" placeholder="" class="col-xs-10 col-sm-5" value="<?php echo ($currentmenu["o"]); ?>">
			<span class="help-inline col-xs-12 col-sm-7">
				<span class="middle">越小越靠前</span>
			</span>
		</div>
	</div>
	<div class="space-4"></div>
	<div class="form-group">
		<label class="col-sm-1 control-label no-padding-right" for="form-field-2"> 页面提示</label>
		<div class="col-sm-9">
			<textarea name="tips" id="tips" placeholder="页面提示" class="col-xs-10 col-sm-5" rows="5"><?php echo ($currentmenu["tips"]); ?></textarea>
			<span class="help-inline col-xs-12 col-sm-7">
				<span class="middle"></span>
			</span>
		</div>
	</div>

	<div class="space-4"></div>
	<div class="col-md-offset-2 col-md-9">
		<button class="btn btn-info" type="submit">
			<i class="icon-ok bigger-110"></i>
			提交
		</button>

		&nbsp; &nbsp; &nbsp;
		<button class="btn" type="reset">
			<i class="icon-undo bigger-110"></i>
			重置
		</button>
	</div>
</form>
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

<script type="text/javascript">
$(function(){
	var editor = KindEditor.create('textarea[name="tips"]',{
		resizeType : 1,
		allowPreviewEmoticons : false,
		allowImageUpload : false,
		items : [
			'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
			'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
			'insertunorderedlist', '|', 'emoticons','link']
	});
	$(".btn.btn-info").click(function(){
		var title = $("#title").val();
		if(title == ''){
			bootbox.alert({
				buttons: {
				   ok: {
						label: '确定',
						className: 'btn-myStyle'
					}
				},
				message: '菜单名称不能为空。',
				title: "友情提示",
			});
			return false;
		}

	})
})
</script>