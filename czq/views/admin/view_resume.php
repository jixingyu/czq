<?php include('_header.php');?>
<style type="text/css">
ul {
  list-style: none;
  margin: 0;
  padding: 0;
}
</style>
<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">查看用户简历<?php if ($resume['is_deleted']) echo '<span class="text-danger">(已删除)<span>';?></h1>
    </div>
    <!-- /.col-md-12 -->
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                简历信息
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td>用户</td>
                                <td><?php echo $member['email']; ?></td>
                            </tr>
                            <tr>
                                <td>姓名</td>
                                <td><?php echo $resume['real_name']; ?></td>
                            </tr>
                            </tr>
                                <td>性别</td>
                                <td><?php echo $resume['gender'] ? '男' : '女'; ?></td>
                            </tr>
                            </tr>
                                <td>生日</td>
                                <td><?php echo date('Y-m-d', $resume['birthday']); ?></td>
                            </tr>
                            </tr>
                                <td>籍贯</td>
                                <td><?php echo $resume['native_place']; ?></td>
                            </tr>
                            </tr>
                                <td>政治面貌</td>
                                <td><?php echo $resume['political_status']; ?></td>
                            </tr>
                            </tr>
                                <td>参加工作时间</td>
                                <td><?php echo date('Y-m-d', $resume['work_start_time']); ?></td>
                            </tr>
                            </tr>
                                <td>手机</td>
                                <td><?php echo $resume['mobile']; ?></td>
                            </tr>
                            </tr>
                                <td>邮箱</td>
                                <td><?php echo $resume['email']; ?></td>
                            </tr>
                            </tr>
                                <td>毕业院校</td>
                                <td><?php echo $resume['school']; ?></td>
                            </tr>
                            </tr>
                                <td>学历</td>
                                <td><?php echo $resume['degree']; ?></td>
                            </tr>
                            </tr>
                                <td>专业</td>
                                <td><?php echo $resume['major']; ?></td>
                            </tr>
                            </tr>
                                <td>自我评价</td>
                                <td><?php echo $resume['evaluation']; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
        <?php if (!empty($experiences)) : ?>
        <div class="panel panel-yellow">
            <div class="panel-heading">
                工作经验
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>时间</th>
                                <th>公司</th>
                                <th>工作内容</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($experiences as $row) { ?>
                            <tr>
                                <td><?php echo date('Y-m-d', $row['start_time']);?> ~ <?php echo ($row['end_time'] != -1) ? date('Y-m-d', $row['end_time']) : '至今';?></td>
                                <td><?php echo $row['company']; ?></td>
                                <td><?php echo $row['description']; ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
        <?php endif; ?>
    </div>
</div>


<?php include('_footer.php');?>