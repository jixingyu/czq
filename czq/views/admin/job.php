<?php include('_header.php');?>

<div class="row">
    <div class="col-lg-8">
        <h1 class="page-header">
            <?php if (!empty($job['id'])) echo '修改职位'; else echo '新增职位';?>
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
                            <input class="form-control" type="text" name="name" style="width:200px;" value="<?php if (!empty($job['name'])) echo $job['name'];?>">
                        </div>
                        <div class="form-group">
                            <label>所在地区</label>
                            <select id="district" name="district" class="form-control">
                                <?php foreach ($district_list as $v) { ?>
                                    <option value="<?php echo $v;?>"<?php if ($job['district'] == $v) {echo ' selected="selected"';} ?>><?php echo $v;?></option>
                                <?php } ?>
                            </select>
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
                                <?php foreach ($degree_list as $v) { ?>
                                    <option value="<?php echo $v;?>"<?php if ($job['degree'] == $v) {echo ' selected="selected"';$sel_degree = true;} ?>><?php echo $v;?></option>
                                <?php } ?>
                                <option value="-1">自定义</option>
                            </select>
                            <div style="margin-top:10px;" id="custom_degree_div">
                                请填写自定义学历：
                                <input type="text" id="custom_degree" name="custom_degree"<?php if ($job['id'] && !$sel_degree) echo ' value="' . $job['degree'] . '"'; ?> />
                            </div>
                        </div>
                        <div class="form-group">
                            <label>月薪</label>
                            <select id="salary" name="salary" class="form-control">
                                <?php foreach ($salary_list as $v) { ?>
                                    <option value="<?php echo $v;?>"<?php if ($job['salary'] == $v) {echo ' selected="selected"';$sel_salary = true;} ?>><?php echo $v;?></option>
                                <?php } ?>
                                <option value="-1">自定义</option>
                            </select>
                            <div style="margin-top:10px;" id="custom_salary_div">
                                请填写自定义月薪：
                                <input type="text" id="custom_salary" name="custom_salary"<?php if ($job['id'] && !$sel_salary) echo ' value="' . $job['salary'] . '"'; ?> />
                            </div>
                        </div>
                        <div class="form-group">
                            <label>工作年限</label>
                            <select id="working_years" name="working_years" class="form-control">
                                <?php foreach ($working_years_list as $v) { ?>
                                    <option value="<?php echo $v;?>"<?php if ($job['working_years'] == $v) {echo ' selected="selected"';} ?>><?php echo $v;?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>招聘人数</label>
                            <input class="form-control" type="text" name="recruit_number" style="width:200px;" value="<?php if (!empty($job['recruit_number'])) echo $job['recruit_number'];?>">
                        </div>
                        <div class="form-group">
                            <label>职位类型</label>
                            <select id="job_type" name="job_type" class="form-control">
                                <?php foreach ($job_type_list as $v) { ?>
                                    <option value="<?php echo $v;?>"<?php if ($job['job_type'] == $v) {echo ' selected="selected"';} ?>><?php echo $v;?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>职位福利</label>
                            <div>
                            <?php foreach ($benefit_list as $v) :?>
                                <label class="checkbox-inline">
                                    <input type="checkbox" value="<?php echo $v;?>" name="benefit[]" <?php if ($job['benefit'] && in_array($v, $job['benefit'])) echo 'checked="checked"';?>><?php echo $v?>
                                </label>
                            <?php endforeach;?>
                            <div>
                        </div>
                        <div class="form-group">
                            <label>任职要求</label>
                            <textarea class="form-control" name="requirement"><?php if (!empty($job['requirement'])) echo $job['requirement'];?></textarea>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">提交</button>
            </form>
        </div>
    </div>
</div>
<script>
jQuery(function($){
    if ($(this).children('option:selected').val() != '-1') {
        $('#custom_degree_div').hide();
    }
    if ($(this).children('option:selected').val() != '-1') {
        $('#custom_salary_div').hide();
    }
});

$('#degree').change(function(){
    if ($(this).children('option:selected').val() == '-1') {
        $('#custom_degree_div').show();
    } else {
        $('#custom_degree_div').hide();
    }
});
$('#salary').change(function(){
    if ($(this).children('option:selected').val() == '-1') {
        $('#custom_salary_div').show();
    } else {
        $('#custom_salary_div').hide();
    }
});
</script>

<?php include('_footer.php');?>