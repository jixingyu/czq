<?php include('_header.php');?>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">职位管理</h1>
    </div>
    <!-- /.col-md-12 -->
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
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
                                <th>申请人姓名</th>
                                <th>申请人电话</th>
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
                                <td><?php echo $row['real_name'];?></td>
                                <td><?php echo $row['mobile'];?></td>
                                <td><?php echo date('Y-m-d H:i:s', $row['create_time']);?></td>
                                <td><?php switch ($row['status']) {
                                    case 0:
                                        echo '申请中';
                                        break;
                                    case 1:
                                        echo '已发面试通知';
                                        break;
                                    default:
                                        break;
                                }?></td>
                                <td>
                                    <a href="javascript:" onclick="edit_interview(<?php echo $row['id'];?>, '<?php echo $row['interview_time'] ? date('Y-m-d H:i', $row['interview_time']): '';?>', '<?php echo $row['address'] ?: '';?>')">
                                        通知面试
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

<div class="modal fade" id="interviewModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">
                    面试通知
                </h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>面试地点</label>
                    <input class="form-control" type="text" id="address" />
                </div>
                <div class="form-group">
                    <label class="control-label">面试时间</label>
                    <div style="width:50%;" class="input-group date date_interview" data-date-format="yyyy-mm-dd hh:ii" data-link-format="yyyy-mm-dd hh:ii">
                        <input class="form-control" id="interview_time" type="text" readonly>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary" id="post-interview">提交</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
var cur_apply_id = 0;
function edit_interview(apply_id, interview_time, address)
{
    $('#address').val(address);
    $('#interview_time').val(interview_time);

    cur_apply_id = apply_id;
    $('#interviewModal').modal('show');
}

$('.date_interview').datetimepicker({
    language:  'zh-CN',
    weekStart: 1,
    todayBtn:  1,
    autoclose: 1,
    todayHighlight: 1,
    startView: 2,
    forceParse: 0
});

$("#post-interview").click(function(){
    $.post('/admin/job/add_interview/' + cur_apply_id, {
        address : $('#address').val(),
        interview_time : $('#interview_time').val()
    }, function(res) {
        res = $.parseJSON(res);
        if (res.code) {
            location.href = location.href;
        } else {
            alert(res.data);
        }
    });
});
</script>

<?php include('_footer.php');?>