<?php include('_header.php');?>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">用户简历</h1>
    </div>
    <!-- /.col-md-12 -->
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <?php echo $member['email']; ?>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive" style="margin-top:30px">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>更新时间</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($user_resume as $key => $row) :?>
                            <tr>
                                <td><?php echo date('Y-m-d H:i:s', $row['update_time']);?></td>
                                <td><?php echo $row['is_deleted'] ? '已删除' : '未删除';?></td>
                                <td>
                                    <a href="/admin/resume/view/<?php echo $row['id'];?>">查看</a>
                                </td>
                            </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
</div>

<?php include('_footer.php');?>