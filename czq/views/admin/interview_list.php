<?php include('_header.php');?>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">职位管理</h1>
    </div>
    <!-- /.col-md-12 -->
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                面试列表
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive" style="margin-top:30px">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>用户</th>
                                <th>面试时间</th>
                                <th>面试地点</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($interview_list as $key => $row) :?>
                            <tr>
                                <td><?php echo $row['email'];?></td>
                                <td><?php echo date('Y-m-d H:i:s', $row['interview_time']);?></td>
                                <td><?php echo $row['address'];?></td>
                                <td>
                                    <a href="/admin/job/editInterview/<?php echo $row['apply_id']; ?>">
                                        修改面试通知
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>

                    <?php if (!empty($job_list)) echo $this->pagination->create_links();?>
                </div>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
</div>

<?php include('_footer.php');?>