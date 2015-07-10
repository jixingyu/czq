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
        <h1 class="page-header">查看用户简历</h1>
    </div>
    <!-- /.col-md-12 -->
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
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
                                <td>工作年限</td>
                                <td><?php echo $resume['working_years']; ?></td>
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
    </div>
</div>


<?php include('_footer.php');?>