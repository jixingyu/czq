<?php include('_header.php');?>

<div class="row">
    <div class="col-lg-8">
        <h1 class="page-header">
            <?php if (isset($job['id'])) echo '修改职位'; else echo '新增职位';?>
            <a class="pull-right admin-back" href="/admin/job">返回</a>
        </h1>

        <div class="text-num">
            <form role="form" action="/admin/job/editJob<?php if (isset($job['id'])) echo '/' . $job['id'];?>" method="post" enctype="multipart/form-data">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        职位设置
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label>职位名称</label>
                            <input type="text" name="name" style="width:200px;" value="<?php if (!empty($job['name'])) echo $job['name'];?>">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">提交</button>
            </form>
        </div>
    </div>
</div>

<?php include('_footer.php');?>