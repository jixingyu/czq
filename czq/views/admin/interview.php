<?php include('_header.php');?>

<div class="row">
    <div class="col-lg-8">
        <h1 class="page-header">
            <?php if ($edit_interview) echo '修改面试通知'; else echo '发送面试通知';?>
        </h1>

        <div class="text-num">
            <form role="form" action="/admin/job/editInterview/<?php echo $apply_id;?>" method="post" enctype="multipart/form-data">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        公司设置
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label>面试地点</label>
                            <input class="form-control" type="text" name="address" value="<?php if (!empty($interview['address'])) echo $interview['address'];?>" />
                        </div>
                        <div class="form-group">
                            <label class="control-label">面试时间</label>
                            <div style="width:50%;" class="input-group date date_interview" data-date-format="yyyy-mm-dd hh:ii" data-link-format="yyyy-mm-dd hh:ii">
                                <input class="form-control" name="interview_time" type="text" value="<?php if (!empty($interview['interview_time'])) echo date('Y-m-d H:i', $interview['interview_time']);?>" readonly>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">提交</button>
            </form>
        </div>
    </div>
</div>

<script>
$('.date_interview').datetimepicker({
    language:  'zh-CN',
    weekStart: 1,
    todayBtn:  1,
    autoclose: 1,
    todayHighlight: 1,
    startView: 2,
    forceParse: 0
});
</script>

<?php include('_footer.php');?>