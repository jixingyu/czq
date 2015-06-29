<?php include('_header.php');?>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">公司管理</h1>
    </div>
    <!-- /.col-md-12 -->
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                公司列表
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive" style="margin-top:30px">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th><a href="/admin/company/editCompany"><i class="fa fa-plus-square"> 添加</a></th>
                                <th>名称</th>
                                <th>修改</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($company_list as $key => $row) :?>
                            <tr id="tr<?php echo $row['id'];?>">
                                <td><?php echo $key + 1;?></td>
                                <td><?php echo $row['name'];?></td>
                                <td><a href="/admin/company/editCompany/<?php echo $row['id'];?>">修改</a>
                                    <a onclick="del(<?php echo $row['id'];?>)" href="javascript:;">删除</a>
                                </td>
                            </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>

                    <?php if (!empty($company_list)) echo $this->pagination->create_links();?>
                </div>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
</div>
<script type="text/javascript">
    function del(id) {
        if (confirm('确定要删除吗？')) {
            var tr = $("#tr" + id);
            $.get("/admin/company/deleteCompany/" + id,function(data){
                data = JSON.parse(data);
                if (data.code == 1) {
                    tr.remove();
                } else if (data.message) {
                    alert(data.message);
                } else {
                    alert('操作失败');
                }
            });
        }
    };
</script>

<?php include('_footer.php');?>