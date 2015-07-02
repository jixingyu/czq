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
                        <div class="form-group">
                            <label>公司</label>
                            <select name="company_id" class="form-control">
                                <?php foreach ($company_list as $company) { ?>
                                    <option value="<?php echo $company['id'];?>"<?php if ($job['company_id'] == $company['id']) echo ' selected="selected"'; ?>><?php echo $company['name'];?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>学历要求</label>
                            <select id="degree" name="degree" class="form-control">
                                <option value="-1">自定义</option>
                                <?php foreach ($degree_list as $v) { ?>
                                    <option value="<?php echo $v;?>"<?php if ($job['degree'] == $v) {echo ' selected="selected"';$sel_degree = true;} ?>><?php echo $v;?></option>
                                <?php } ?>
                            </select>
                            <input type="text" id="custom_degree" name="custom_degree"<?php if ($job['id'] && !$sel_degree) echo ' value="' . $job['degree'] . '"'; elseif (!$sel_degree) echo ' style="display:none"'; ?> />
                        </div>
                        <div class="form-group">
                            <label>月薪</label>
                            <select id="salary" name="salary" class="form-control">
                                <option value="-1">自定义</option>
                                <?php foreach ($salary_list as $v) { ?>
                                    <option value="<?php echo $v;?>"<?php if ($job['salary'] == $v) {echo ' selected="selected"';$sel_salary = true;} ?>><?php echo $v;?></option>
                                <?php } ?>
                            </select>
                            <input type="text" id="custom_salary" name="custom_salary"<?php if ($job['id'] && !$sel_salary) echo ' value="' . $job['salary'] . '"'; elseif (!$sel_degree) echo ' style="display:none"'; ?> />
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">提交</button>
            </form>
        </div>
    </div>
</div>
<script>
$('#degree').change(function(){
    if ($(this).children('option:selected').val() == '-1') {
        $('#custom_degree').show();
    } else {
        $('#custom_degree').hide();
    }
});
$('#salary').change(function(){
    if ($(this).children('option:selected').val() == '-1') {
        $('#custom_salary').show();
    } else {
        $('#custom_salary').hide();
    }
});
</script>

<?php include('_footer.php');?>