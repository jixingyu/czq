<?php include('_header.php');?>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">用户管理</h1>
    </div>
    <!-- /.col-md-12 -->
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                用户列表
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive" style="margin-top:30px">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th><a href="/admin/member/editMember"><i class="fa fa-plus-square"> 添加</a></th>
                                <th>邮箱</th>
                                <th>姓名</th>
                                <th>电话</th>
                                <th>激活状态</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($member_list as $key => $row) :?>
                            <tr>
                                <td><?php echo $key + 1;?></td>
                                <td><?php echo $row['email'];?></td>
                                <td><?php echo $row['real_name'];?></td>
                                <td><?php echo $row['mobile'];?></td>
                                <td><?php echo $row['is_active'] ? '已激活' : '未激活';?></td>
                                <td>
                                    <a href="/admin/member/editMember/<?php echo $row['user_id'];?>">修改</a>
                                </td>
                            </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>

                    <?php if (!empty($member_list)) echo $this->pagination->create_links();?>
                </div>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
</div>

<?php include('_footer.php');?>