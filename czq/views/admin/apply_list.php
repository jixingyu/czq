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
                职位申请列表
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive" style="margin-top:30px">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>职位名称</th>
                                <th>公司名称</th>
                                <th>申请人</th>
                                <th>申请时间</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($apply_list as $key => $row) :?>
                            <tr>
                                <td><?php echo $row['job'];?></td>
                                <td><?php echo $row['company'];?></td>
                                <td><?php echo $row['email'];?></td>
                                <td><?php echo date('Y-m-d H:i:s', $row['create_time']);?></td>
                                <td><?php switch ($row['status']) {
                                    case 0:
                                        echo '申请中';
                                        break;
                                    case 1:
                                        echo '已发面试通知';
                                        break;
                                    case 2:
                                        echo '面试结束';
                                        break;
                                    default:
                                        break;
                                }?></td>
                                <td>
                                    <a href="/admin/job/view/<?php echo $row['job_id']; ?>">
                                        查看职位
                                    </a>
                                    <a href="/admin/resume/view/<?php echo $row['resume_id']; ?>">
                                        查看简历
                                    </a>
                                    <?php if ($row['status'] == 0) : ?>
                                    <a href="/admin/job/editInterview/<?php echo $row['id']; ?>">
                                        发送面试通知
                                    </a>
                                    <?php elseif ($row['status'] == 1) : ?>
                                    <a href="/admin/job/editInterview/<?php echo $row['id']; ?>">
                                        修改面试通知
                                    </a>
                                    <?php endif; ?>
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