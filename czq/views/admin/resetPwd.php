<?php include('_header.php');?>

<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">重置密码</h1>
                <div class="col-lg-6">

                    <?php if (!empty($error)): ?>
                    <div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <?php echo $error;?>
                    </div>
                    <?php elseif ($status): ?>
                    <div class="alert alert-success alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        重置密码成功
                    </div>
                    <?php endif; ?>

                    <form role="form" action="/admin/passport/resetPwd" method="post">
                        <div class="form-group">
                            <label>密码</label>
                            <input class="form-control" name="password" type="password">
                        </div>
                        <div class="form-group">
                            <label>确认密码</label>
                            <input class="form-control" name="confirm" type="password">
                        </div>
                        <button type="submit" class="btn btn-default">提交</button>
                    </form>
                </div>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->

<?php include('_footer.php');?>