<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>找回密码</title>

    <link href="<?php echo base_url('assets/css/bootstrap.min.css');?>" rel="stylesheet">

    <script src="<?php echo base_url('assets/js/jquery.js');?>"></script>
    <script src="<?php echo base_url('assets/js/bootstrap.min.js');?>"></script>

<body>
	<div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4" style="margin-top: 10%;">
                <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <?php echo $error;?>
                </div>
                <?php endif; ?>
                <?php if (!empty($msg)): ?>
                <div class="alert alert-success alert-dismissable">
                    <?php echo $msg;?>
                </div>
            	<?php else: ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">找回密码</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" action="/passport/find_pwd" method="post">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="邮箱" name="email" type="email" autofocus>
                                </div>
                                <input type="submit" class="btn btn-lg btn-success btn-block" value="提交" />
                            </fieldset>
                        </form>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
	</div>
</body>
</html>