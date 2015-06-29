<?php include('_header.php');?>

<div class="row">
    <div class="col-lg-8">
        <h1 class="page-header">
            <?php if (isset($company['id'])) echo '修改公司'; else echo '新增公司';?>
            <a class="pull-right admin-back" href="/admin/company">返回</a>
        </h1>

        <div class="text-num">
            <form role="form" action="/admin/company/editCompany<?php if (isset($company['id'])) echo '/' . $company['id'];?>" method="post" enctype="multipart/form-data">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        公司设置
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label>公司名</label>
                            <input type="text" name="name" style="width:200px;" value="<?php if (!empty($company['name'])) echo $company['name'];?>">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">提交</button>
            </form>
        </div>
    </div>
</div>

<?php include('_footer.php');?>