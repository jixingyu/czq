<?php include('_header.php');?>

<div class="row">
    <div class="col-lg-8">
        <h1 class="page-header">
            修改密码
            <a class="pull-right admin-back" href="/admin/member/editMember/<?php echo $member_id; ?>">返回</a>
        </h1>

        <div class="text-num">
            <form role="form" action="/admin/member/resetPwd/<?php echo $member_id;?>" method="post">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        修改密码
                    </div>
                    <div class="panel-body">

                        <div class="form-group">
                            <label>密码</label>
                            <input class="form-control" type="password" name="password">
                        </div>
                        <div class="form-group">
                            <label>确认密码</label>
                            <input class="form-control" type="password" name="confirm_password">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">提交</button>
            </form>
        </div>

    </div>
</div>

<?php include('_footer.php');?>