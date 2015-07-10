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
        <h1 class="page-header">查看职位信息</h1>
    </div>
    <!-- /.col-md-12 -->
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                职位信息
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td>职位名称</td>
                                <td><?php echo $name; ?></td>
                            </tr>
                            <tr>
                                <td>公司</td>
                                <td><?php echo $company; ?></td>
                            </tr>
                            <tr>
                                <td>学历要求</td>
                                <td><?php echo $degree; ?></td>
                            </tr>
                            <tr>
                                <td>月薪</td>
                                <td><?php echo $salary; ?></td>
                            </tr>
                            <tr>
                                <td>地区</td>
                                <td><?php echo $district; ?></td>
                            </tr>
                            <tr>
                                <td>工作年限</td>
                                <td><?php echo $working_years; ?></td>
                            </tr>
                            <tr>
                                <td>招聘人数</td>
                                <td><?php echo $recruit_number; ?></td>
                            </tr>
                            <tr>
                                <td>类型</td>
                                <td><?php echo $job_type; ?></td>
                            </tr>
                            <tr>
                                <td>福利</td>
                                <td><ul><?php foreach ($benefit as $value) {
                                    echo '<li>' . $value . '</li>';
                                } ?></ul></td>
                            </tr>
                            <tr>
                                <td>要求</td>
                                <td><?php echo $requirement; ?></td>
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