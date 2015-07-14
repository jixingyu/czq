<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>重置密码</title>

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
                <div class="alert alert-danger alert-dismissable">
                    <?php echo $msg;?>
                </div>
                <?php elseif (!empty($success_msg)): ?>
                <div class="alert alert-success alert-dismissable">
                    <?php echo $success_msg;?>
                </div>
                <?php else: ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">重置密码</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" action="<?php echo $reset_url;?>" method="post">
                            <fieldset>
                                <div class="form-group">
                                    <label>密码</label>
                                    <input class="form-control" name="password" type="password" autofocus>
                                </div>
                                <div class="form-group">
                                    <label>确认密码</label>
                                    <input class="form-control" name="confirm_password" type="password">
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