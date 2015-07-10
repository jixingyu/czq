<?php include('_header.php');?>

<div class="row">
    <div class="col-lg-8">
        <h1 class="page-header">
            <?php if (isset($member['user_id'])) echo '修改用户'; else echo '新增用户';?>
            <a class="pull-right admin-back" href="/admin/member">返回</a>
        </h1>

        <div class="text-num">
            <form role="form" action="/admin/member/editMember<?php if (isset($member['user_id'])) echo '/' . $member['user_id'];?>" method="post">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        用户设置
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label>邮箱</label>
                            <?php if (isset($member['user_id'])) : ?>
                            <input class="form-control" type="text" name="email" value="<?php echo $member['email']; ?>" disabled="disabled">
                            <?php else : ?>
                            <input class="form-control" type="text" name="email">
                            <?php endif; ?>
                        </div>
                        <?php if (!isset($member['user_id'])) : ?>
                        <div class="form-group">
                            <label>密码</label>
                            <input class="form-control" type="password" name="password">
                        </div>
                        <div class="form-group">
                            <label>确认密码</label>
                            <input class="form-control" type="password" name="confirm_password">
                        </div>
                        <?php endif; ?>
                        <div class="form-group">
                            <label>姓名</label>
                            <input class="form-control" type="text" name="real_name" value="<?php if (!empty($member['real_name'])) echo $member['real_name'];?>">
                        </div>
                        <div class="form-group">
                            <label>手机</label>
                            <input class="form-control" type="text" name="mobile" value="<?php if (!empty($member['mobile'])) echo $member['mobile'];?>">
                        </div>
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="is_active"<?php if (!empty($member['is_active'])) echo ' checked="checked"';?>> 是否激活
                            </label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">提交</button>
                <?php if (isset($member['user_id'])) : ?>
                <a href="/admin/member/resetPwd/<?php echo $member['user_id']; ?>" class="btn btn-primary">重置密码</a>
                <?php endif; ?>
            </form>
        </div>

    </div>
</div>

<?php include('_footer.php');?>