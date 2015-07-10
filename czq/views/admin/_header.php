<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>后台</title>

    <link href="<?php echo base_url('assets/css/bootstrap.min.css');?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/bootstrap-datetimepicker.min.css');?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/plugins/metisMenu/metisMenu.min.css');?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/sb-admin-2.css');?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/font-awesome-4.1.0/css/font-awesome.min.css');?>" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url('assets/css/czq.css');?>" rel="stylesheet">

    <script src="<?php echo base_url('assets/js/jquery.js');?>"></script>
    <script src="<?php echo base_url('assets/js/bootstrap.min.js');?>"></script>
    <script src="<?php echo base_url('assets/js/bootstrap-datetimepicker.min.js');?>"></script>
    <script src="<?php echo base_url('assets/js/bootstrap-datetimepicker.zh-CN.js');?>"></script>
    <script src="<?php echo base_url('assets/js/plugins/metisMenu/metisMenu.min.js');?>"></script>
    <script src="<?php echo base_url('assets/js/sb-admin-2.js');?>"></script>
</head>

<body>

    <div class="wrapper">

<?php
    $tab    = $this->uri->segment(2);
    $subTab = $this->uri->segment(3) ?: 'index';
?>

		<!-- Navigation -->
		<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
		    <div class="navbar-header">
		        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
		            <span class="sr-only">Toggle navigation</span>
		            <span class="icon-bar"></span>
		            <span class="icon-bar"></span>
		            <span class="icon-bar"></span>
		        </button>
		        <a class="navbar-brand" href="index.html">Admin</a>
		    </div>
		    <!-- /.navbar-header -->

		    <ul class="nav navbar-top-links navbar-right">
		        <!-- /.dropdown -->
		        <li class="dropdown">
		            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
		            	<?php echo $username;?>
		                <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
		            </a>
		            <ul class="dropdown-menu dropdown-user">
		                <li><a href="/admin/passport/resetPwd"><i class="fa fa-gear fa-fw"></i> 重置密码</a>
		                </li>
		                <li class="divider"></li>
		                <li><a href="/admin/logout"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
		                </li>
		            </ul>
		            <!-- /.dropdown-user -->
		        </li>
		        <!-- /.dropdown -->
		    </ul>
		    <!-- /.navbar-top-links -->

		    <div class="navbar-default sidebar" role="navigation">
		        <div class="sidebar-nav navbar-collapse">
		            <ul class="nav" id="side-menu">
		                <li<?php echo ($tab == 'job') ? ' class="active"' : '';?>>
		                    <a href="#"><i class="fa fa-edit fa-fw"></i> 职位管理<span class="fa arrow"></span></a>
		                    <ul class="nav nav-second-level">
		                        <li>
		                            <a href="/admin/job"<?php if ($tab == 'job' && $subTab == 'index') echo ' class="active"'; ?>>职位列表</a>
		                        </li>
		                        <li>
		                            <a href="/admin/job/apply_list"<?php if ($tab == 'job' && $subTab == 'apply_list') echo ' class="active"'; ?>>职位申请列表</a>
		                        </li>
		                        <li>
		                            <a href="/admin/job/interview_list"<?php if ($tab == 'job' && $subTab == 'interview_list') echo ' class="active"'; ?>>面试列表</a>
		                        </li>
		                    </ul>
		                </li>
	
		                <li<?php echo ($tab == 'company') ? ' class="active"' : '';?>>
		                    <a href="/admin/company"><i class="fa fa-edit fa-fw"></i> 公司管理</a>
		                </li>
	
		                <li<?php echo ($tab == 'member') ? ' class="active"' : '';?>>
		                    <a href="/admin/member"><i class="fa fa-edit fa-fw"></i> 用户管理</a>
		                </li>
		            </ul>
		        </div>
		        <!-- /.sidebar-collapse -->
		    </div>
		    <!-- /.navbar-static-side -->
		</nav>

		<div id="page-wrapper">
		    <div class="container-fluid">

    	        <?php if (!empty($error)): ?>
		        <div class="alert alert-danger alert-dismissable">
		            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		            <?php echo $error;?>
		        </div>
		        <?php elseif (!empty($status)): ?>
		        <div class="alert alert-success alert-dismissable">
		            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		            修改成功
		        </div>
		        <?php endif; ?>
